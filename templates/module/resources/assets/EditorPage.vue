<template>
  <div class="admin-editor-page">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>上传内容管理
        <small v-if="form.id">编辑上传内容</small>
        <small v-else>新建上传内容</small>
      </h1>
      <ol class="breadcrumb">
        <li>
          <router-link to="/"><i class="fa fa-dashboard"></i> 总览</router-link>
        </li>
        <li>
          <router-link to="/upload/list">上传内容管理</router-link>
        </li>
        <li class="active" v-if="form.id">编辑上传内容</li>
        <li class="active" v-else>新建上传内容</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="box box-default">

        <div class="box-header with-border">
          <el-button type="default" @click="onCancel" icon="close">返回</el-button>
          <el-button type="primary" @click="onSave" icon="check" :loading="saving||loading">
            保存
          </el-button>
        </div>
        <!-- /.box-header -->

        <!-- form start -->
        <div class="box-body">
          <el-alert class="missing-errors" v-if="missingErrors.length" v-for="errorMessage in missingErrors"
                    :key="errorMessage"
                    :title="errorMessage" type="error" show-icon></el-alert>

          <!-- form start -->
          <el-form ref="form" :model="form" label-width="200px" v-loading="loading">
            <el-form-item label="ID" v-if="form.id">
              <el-input v-model="form.id" disabled></el-input>
            </el-form-item>
            <el-form-item label="Name" prop="name" :error="errors.name">
              <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="Description" prop="description" :error="errors.description">
              <el-input v-model="form.description"></el-input>
            </el-form-item>
            <el-form-item label="Type" prop="type" :error="errors.type">
              <el-input v-model="form.type"></el-input>
            </el-form-item>
            <el-form-item label="Disk" v-if="form.id">
              <el-input v-model="form.disk" disabled></el-input>
            </el-form-item>
            <el-form-item label="Path" v-if="form.id">
              <el-input v-model="form.path" disabled></el-input>
            </el-form-item>
            <el-form-item label="Size" v-if="form.id">
              <el-input v-model="form.size" disabled></el-input>
            </el-form-item>

            <el-form-item label="File" prop="file" :error="errors.file">
              <input type="file" class="form-control" id="field-file" v-on:change="onFileChange" name="file">
              <p class="assist-tip" v-if="form.url">
                <a :href="form.url" target="_blank"><i class="el-icon-view"></i> {{form.url}}</a>
              </p>
            </el-form-item>

            <el-form-item label="User ID" v-if="form.id">
              <el-input v-model="form.user_id" disabled></el-input>
            </el-form-item>
            <el-form-item label="Uploadable ID" v-if="form.id">
              <el-input v-model="form.uploadable_id" disabled></el-input>
            </el-form-item>
            <el-form-item label="Uploadable Type" v-if="form.id">
              <el-input v-model="form.uploadable_type" disabled></el-input>
            </el-form-item>
            <el-form-item label="Created At" v-if="form.id">
              <el-input v-model="form.created_at" disabled></el-input>
            </el-form-item>
          </el-form>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <el-button type="default" @click="onCancel" icon="close">返回</el-button>
          <el-button type="primary" @click="onSave" icon="check" :loading="saving||loading">
            保存
          </el-button>
        </div>

      </div>

    </section>
    <!-- /.content -->

  </div>
</template>

<script type="javascript">
  import {mixin} from "resources/assets/js/commons/EditorHelper.js";

  export default  {
    mixins: [mixin],
    data: function () {
      return {
        form: {
          id: null,
          name: '',
          description: '',
          type: '',
          file: null
        }
      };
    },
    components: {},
    computed: {
      resource: function () {
        var resourceURL = '/upload';
        return (this.form.id ? resourceURL + '/' + this.form.id : resourceURL);// + '?_with=roles,permissions';
      }
    },
    created: function () {
      this.loading = true;
      let loads = [];
      if (this.$route.params.id) {
        this.form.id = this.$route.params.id;
        loads.push(axios.get(this.resource));
      }

      Promise.all(loads).then(results => {
        this.form = results[0].data.data;
        this.loading = false;
      }).catch(err => {
        this.loading = false;
      });
    },
    methods: {
      onSave: function (event) {
        this._onSave(event).then(result => {
          this.$router.replace('/upload/' + result.data.data.id + '/edit');
          this.form = result.data.data;
          this.form.permissions = this.form.permissions.map(permission => permission.id);
        });
      },
      onCancel: function (event) {
        this.$router.back();
      },
      onFileChange: function (event) {
        this.form.file = event.target.files[0];
      }
    }
  };
</script>

<style lang="scss" scoped>

</style>
