import http from 'k6/http';
import { sleep } from 'k6';

export let options = {
  stages: [
    { duration: '3s', target: 200 },   // Ramp-up to 10 users
    { duration: '6s', target: 450 }, // Spike to 100 users
    { duration: '8s', target: 600 },   // Ramp-down to 0 users
  ],
};

export default function () {
  http.get('http://localhost:7777/api/v1/users/carousel/most-viewed');
  sleep(1);
}
