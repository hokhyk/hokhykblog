<template>
  <div class="filter-container">
    <el-row :gutter="10">
      <el-col
        class="filter-input"
        :xs="24"
        :sm="8"
        :md="6"
        :lg="6"
        :xl="4"
      >
        <el-input
          v-model="listQuery.name"
          placeholder="Name"
          class="filter-item"
          @keyup.enter.native="handleFilter"
        />
      </el-col>
      <el-col
        class="filter-input"
        :xs="24"
        :sm="8"
        :md="6"
        :lg="6"
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
export default {
  name: 'RoleFilter',
  props: {
    'listQuery': {
      type: Object,
      default: function () {
        return {}
      }
    }
  },
  methods: {
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
</style>
