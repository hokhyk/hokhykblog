<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="dialogLoading"
      :model="form"
      label-width="120px"
      :rules="rules"
    >
      <el-form-item
        label="Title："
        prop="name"
      >
        <el-input
          v-model="form.name"
          placeholder="Input Post title"
        />
      </el-form-item>
      <el-form-item
        label="Category："
        prop="category_id"
      >
        <el-select
          v-model="form.category_id"
          filterable
          placeholder="Please select category"
          style="width:30%"
        >
          <el-option
            v-for="item in categoryList"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item
        label="Author："
        prop="author"
      >
        <el-input
          v-model="form.author"
          placeholder="Input author name"
        />
      </el-form-item>
      <el-form-item label="Origin：">
        <el-input
          v-model="form.source"
          placeholder="Please input origin"
        />
      </el-form-item>
      <el-form-item label="Keywords：">
        <el-input
          v-model="form.keyword"
          placeholder="Please input keywords."
        />
      </el-form-item>
      <el-form-item label="Description：">
        <el-input
          v-model="form.description"
          type="textarea"
          :rows="2"
          placeholder="Please input description."
        />
      </el-form-item>
      <el-form-item
        label="Content："
        prop="content"
      >
        <Tinymce
          ref="editor"
          v-model="form.content"
          :height="400"
        />
        <input
          id="upfile"
          type="file"
          style="display:none;"
        >
      </el-form-item>
      <el-form-item label="Publishing time：">
        <el-date-picker
          v-model="form.created_at"
          type="datetime"
          placeholder="Please select publishing time"
        />
        <span class="tips">（If left blank system time will be used.）</span>
      </el-form-item>
      <el-form-item label="Order：">
        <el-input
          v-model="form.sort"
          type="number"
          placeholder="Please input order number."
        />
      </el-form-item>
      <el-form-item
        label="Status："
        prop="status"
      >
        <template>
          <el-radio
            v-model="form.status"
            label="0"
          >
            Draft
          </el-radio>
          <el-radio
            v-model="form.status"
            label="1"
          >
            Published
          </el-radio>
        </template>
      </el-form-item>
      <el-form-item>
        <el-button
          type="primary"
          @click="submit"
        >
          Save
        </el-button>
        <el-button @click="onCancel">
          Cancel
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { getAllList as getCategoryList } from '@/api/article/category'
import { createArticle, getArticle, updateArticle } from '@/api/article/article'
import Tinymce from '@/components/Tinymce'

export default {
  components: {
    Tinymce
  },
  data () {
    return {
      form: {
        name: '',
        author: '',
        source: '',
        category_id: null,
        keyword: null,
        description: null,
        content: null,
        sort: null,
        status: null,
        created_at: null
      },
      categoryList: [],
      dialogLoading: false,
      rules: {
        name: [{ required: true, message: 'Title is required.', trigger: 'blur' }],
        category_id: [{ required: true, message: 'Category cannot be empty.', trigger: 'blur' }],
        status: [{ required: true, message: 'Status cannot be empty.', trigger: 'change' }],
        content: [{ required: true, message: 'Content cannot be empty.', trigger: 'blur' }]
      },
      id: null
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      getCategoryList({}).then(response => {
        this.categoryList = response.result.data
        if (this.$route.params.id) {
          this.id = this.$route.params ? this.$route.params.id : null
          this.getArticle()
        } else {
          this.$set(this.$data, 'form', {
            status: '1',
            sort: '0'
          })
        }
      })
    },
    getArticle () {
      this.dialogLoading = true
      getArticle(this.id).then((res) => {
        this.dialogLoading = false
        const data = res.result.data
        this.form = data
        this.form.category_id = data.category.data.id
        this.form.status = data.status === 0 ? '0' : '1'
      })
    },
    submit () {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          this.dialogLoading = true
          if (!this.id) {
            this.createdArticle()
          } else {
            this.updateArticle()
          }
        }
      })
    },
    onCancel () {
      this.$router.push('/article/list')
    },
    createdArticle () {
      createArticle(this.form).then(() => {
        this.dialogLoading = false
        this.$message({
          message: 'Successfully Added.',
          type: 'success'
        })
        this.$router.push('/article/list')
      }).catch(() => {
        this.dialogLoading = false
      })
    },
    updateArticle () {
      updateArticle(this.form).then(() => {
        this.dialogLoading = false
        this.$message({
          message: 'Successfully modified.',
          type: 'success'
        })
        this.$router.push('/article/list')
      }).catch(() => {
        this.dialogLoading = false
      })
    }
  }
}
</script>

<style scoped>
.tips {
  font-size: 12px;
}
</style>
