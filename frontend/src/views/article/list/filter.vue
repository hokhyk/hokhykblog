<template>
  <div class="filter-container">
    <el-row :gutter="10">
      <el-col
        class="filter-input"
        :xs="24"
        :sm="8"
        :md="6"
        :lg="4"
        :xl="4"
      >
        <el-input
          v-model="listQuery.name"
          placeholder="title"
          class="filter-item"
          @keyup.enter.native="handleFilter"
        />
      </el-col>
      <el-col
        class="filter-input"
        :xs="24"
        :sm="8"
        :md="6"
        :lg="4"
        :xl="4"
      >
        <el-input
          v-model="listQuery.author"
          placeholder="Author"
          class="filter-item"
          clearable
          @keyup.enter.native="handleFilter"
        />
      </el-col>
      <el-col
        class="filter-input"
        :xs="24"
        :sm="8"
        :md="6"
        :lg="4"
        :xl="4"
      >
        <el-input
          v-model="listQuery.source"
          placeholder="Origin"
          class="filter-item"
          @keyup.enter.native="handleFilter"
        />
      </el-col>
      <el-col
        class="filter-input"
        :xs="24"
        :sm="8"
        :md="6"
        :lg="4"
        :xl="4"
      >
        <el-select
          v-model="listQuery.category_id"
          placeholder="Category"
          clearable
          style="width: 100%"
          class="filter-item"
        >
          <el-option
            v-for="item in categoryOptions"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-col>
      <el-col
        class="filter-input"
        :xs="24"
        :sm="8"
        :md="6"
        :lg="4"
        :xl="4"
      >
        <el-select
          v-model="listQuery.status"
          placeholder="Status"
          clearable
          style="width: 100%"
          class="filter-item"
        >
          <el-option
            v-for="item in statusOptions"
            :key="item.key"
            :label="item.display_name"
            :value="item.key"
          />
        </el-select>
      </el-col>
      <el-col
        class="filter-input"
        :xs="24"
        :sm="8"
        :md="6"
        :lg="4"
        :xl="4"
      >
        <el-button
          class="filter-item"
          type="primary"
          icon="el-icon-search"
          @click="handleFilter"
        >
          Search
        </el-button>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import { getAllList as getCategoryList } from '@/api/article/category'

export default {
  name: 'ArticleFilter',
  props: {
    'listQuery': {
      type: Object,
      default: function () {
        return {}
      }
    }
  },
  data () {
    return {
      statusOptions: [
        { key: 1, display_name: 'Published' },
        { key: 0, display_name: 'Draft' }
      ],
      categoryOptions: []
    }
  },
  mounted () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      getCategoryList({}).then(response => {
        this.categoryOptions = response.result.data
      })
    },
    handleFilter () {
      this.$parent.handleFilter()
    }
  }
}
</script>
<style scoped>
.filter-input {
  margin-bottom: 10px;
}
</style>

