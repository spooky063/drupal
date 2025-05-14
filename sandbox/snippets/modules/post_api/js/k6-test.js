import http from 'k6/http';
import { group, check } from 'k6';
import { Trend, Rate, Counter } from 'k6/metrics';

const BASE_URL = __ENV.BASE_URL;

const groups = {
  'list_post': {
    endpoints: {
      'post_list_json': {
        'path': '/jsonapi/node/post?sort=nid&page[limit]=10&fields[node--post]=nid,title,field_content&fields[user--user]=display_name&include=uid',
        'tag': 'jsonapi'
      },
      'post_list_node': {
        'path': '/api/node/posts',
        'tag': 'node'
      },
      'post_list_postgres': {
        'path': '/api/postgres_view/posts',
        'tag': 'postgres'
      },
      'post_list_rest': {
        'path': '/api/rest/posts?_format=json',
        'tag': 'rest'
      },
      'post_list_symfony': {
        'path': '/api/symfony/posts',
        'tag': 'symfony'
      },
    },
    codeExpected: 200
  },
  'paginate_post': {
    endpoints: {
      'post_paginate_json': {
        'path': '/jsonapi/node/post?sort=nid&page[offset]=10&page[limit]=10&fields[node--post]=nid,title,field_content&fields[user--user]=display_name&include=uid',
        'tag': 'jsonapi'
      },
      'post_paginate_node': {
        'path': '/api/node/posts?page=2&limit=10',
        'tag': 'node'
      },
      'post_paginate_postgres': {
        'path': '/api/postgres_view/posts?page=2&limit=10',
        'tag': 'postgres'
      },
      'post_paginate_rest': {
        'path': '/api/rest/posts?_format=json&page=2&limit=10',
        'tag': 'rest'
      },
      'post_paginate_symfony': {
        'path': '/api/symfony/posts?page=2&limit=10',
        'tag': 'symfony'
      }
    },
    codeExpected: 200
  },
  'get_post': {
    endpoints: {
      'post_item_json': {
        'path': '/jsonapi/node/post/d271ba71-4b52-4c66-a259-39ad2864bb88',
        'tag': 'jsonapi'
      },
      'post_item_node': {
        'path': '/api/node/posts/101',
        'tag': 'node'
      },
      'post_item_postgres': {
        'path': '/api/postgres_view/posts/101',
        'tag': 'postgres'
      },
      'post_item_rest': {
        'path': '/api/rest/posts/101?_format=json',
        'tag': 'rest'
      },
      'post_item_symfony': {
        'path': '/api/symfony/posts/101',
        'tag': 'symfony'
      },
    },
    codeExpected: 200
  }
};

const endpointMetrics = {};
Object.entries(groups).forEach(([name, group]) => {
  Object.entries(group.endpoints).forEach(([label]) => {
    if (!endpointMetrics[name]) {
      endpointMetrics[name] = [];
    }
    if (!endpointMetrics[name][label]) {
      endpointMetrics[name][label] = {
        duration: new Trend(`duration_${label}`),
        success: new Rate(`success_${label}`),
        failures: new Counter(`failures_${label}`),
      };
    }
  });
});

const vus = 10;
const iterations = 1;
export const options = {
  vus: vus,
  iterations: vus * iterations,
  thresholds: {
    'http_req_duration': ['p(95)<7000'],
    'http_req_failed': ['rate<0.01'],
    'checks': ['rate>0.99'],
  }
};

export default function () {
  let headers = { 'Content-Type': 'application/json' };

  Object.entries(groups).forEach(([name, grp]) => {
    group(name, () => {
      for (const [label, {path, tag}] of Object.entries(grp.endpoints)) {
        const encodedPath = encodeURI(path);
        const url = `${BASE_URL}${encodedPath}`;

        if (label.endsWith('_json')) {
          headers = {
            'Accept': 'application/vnd.api+json',
            'Content-Type': 'application/vnd.api+json'
          };
        }

        const res = http.get(url, { tags: { group: name, tech: tag }}, { headers });

        const ok = res.status === grp.codeExpected;

        endpointMetrics[name][label].duration.add(res.timings.duration + res.timings.tls_handshaking, { group: name, tech: tag });
        endpointMetrics[name][label].success.add(ok, {group: name, tech: tag });
        if (!ok) {
          endpointMetrics[name][label].failures.add(1, { group: name, tech: tag });
        }

        check(res, {
          [`${label} status is ${grp.codeExpected}`]: () => ok,
        }, { group: name, tech: tag });
      }
    })
  });
}
