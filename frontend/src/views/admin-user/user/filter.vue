<template>
  <div class="filter-container">
    <el-row :gutter="10">
      <el-col class="filter-input" :xs="24" :sm="8" :md="6" :lg="4" :xl="4">
        <el-input
          v-model="listQuery.name"
          placeholder="用户名"
          class="filter-item"
          @keyup.enter.native="handleFilter"
        />
      </el-col>
      <el-col class="filter-input" :xs="24" :sm="8" :md="6" :lg="4" :xl="4">
        <el-input
          v-model="listQuery.email"
          placeholder="E-mail"
          class="filter-item"
          @keyup.enter.native="handleFilter"
        />
      </el-col>
      <el-col class="filter-input" :xs="24" :sm="8" :md="6" :lg="4" :xl="4">
        <el-select
          v-model="listQuery.role_id"
          placeholder="Role"
          clearable
          style="width: 100%"
          class="filter-item"
        >
          <el-option
            v-for="item in roleOptions"
            :key="item.id"
            :label="item.display_name"
            :value="item.id"
          />
        </el-select>
      </el-col>
      <el-col class="filter-input" :xs="24" :sm="8" :md="6" :lg="4" :xl="4">
        <el-select
          v-model="listQuery.confirmed"
          placeholder="Status"
          clearable
          style="width: 100%"
          class="filter-item"
        >
          <el-option
            v-for="item in confirmedOptions"
            :key="item.key"
            :label="item.display_name"
            :value="item.key"
          />
        </el-select>
      </el-col>
      <el-col class="filter-input" :xs="24" :sm="8" :md="6" :lg="4" :xl="4">
        <el-button
          class="filter-item"
          type="primary"
          icon="el-icon-search"
          @click="handleFilter"
        >
          Search
        </el-button>
        <el-button
          class="filter-item"
          style="margin-left: 10px;"
          type="primary"
          icon="el-icon-edit"
          @click="handleCreate"
        >
          ADD
        </el-button>
      </el-col>
    </el-row>
  </div>
</template>

<script>
// Roles list
import { getList as getRoleList } from '@/api/admin-user/role'

export default {
  name: 'UserFilter',
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
      confirmedOptions: [
        { key: 1, display_name: 'Apply' },
        { key: 0, display_name: 'Disable' }
      ],
      roleOptions: []
    }
  },
  mounted () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      getRoleList({}).then(response => {
        this.roleOptions = response.result.data
      })
    },
    handleFilter () {
      this.$parent.handleFilter()
    },
    handleCreate () {
      this.$parent.handleCreate()
    }
  }
}
</script>
<style scoped>
.filter-input {
  margin-bottom: 10px;
}
.text-label {
  width: 200px;
  border: 1px solid #ddd;
  text-align: justify;
}
</style>

