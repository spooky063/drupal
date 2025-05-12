.PHONY: test
test:
	docker run --rm -it \
  -u $(id -u ${USER}):$(id -g ${USER}) \
  -v "./js:/scripts" \
  -e BASE_URL=http://nginx \
  --network=drupal10_default \
  grafana/k6:1.0.0 \
  run \
  /scripts/k6-test.js

.PHONY: export
export:
	docker run --rm -it \
  -u $(id -u ${USER}):$(id -g ${USER}) \
  -v "./js:/scripts" \
  -e BASE_URL=http://nginx \
  --network=drupal10_default \
  grafana/k6:1.0.0 \
  run \
  --summary-export /scripts/k6-export.json \
  /scripts/k6-test.js
