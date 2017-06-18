export default [
  {
    "data": "name",
    "name": "name",
    "title": "\u540d\u5b57"
  },
  {
    "data": "description",
    "name": "description",
    "title": "\u63cf\u8ff0"
  },
  {
    "data": "url",
    "name": "url",
    "title": "下载",
    "render": function (url) {
      return '<a href="' + url + '" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>';
    }
  },
  {
    "data": "size",
    "name": "size",
    "title": "\u5927\u5c0f",
    "render": function (data) {
      return Math.floor(data / 1024 / 1024 * 1000, 4) / 1000 + ' MB';
    }
  }
];