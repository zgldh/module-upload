export default [
  {path: '/upload', component: () => System.import('./ListPage.vue')},
  // {path: '/upload/create', component: () => System.import('./EditorPage.vue')},
  {path: '/upload/:id/edit', component: () => System.import('./EditorPage.vue')},
];