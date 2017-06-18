export default [
  {path: '/upload', component: () => System.import('./AppPage.vue')},
  {path: '/upload/create', component: () => System.import('./EditorPage.vue')},
  {path: '/upload/:id/edit', component: () => System.import('./EditorPage.vue')},
];