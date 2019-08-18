<template>
  <div>
    <el-tree
      ref="tree"
      class="el-tree-box"
      :data="data"
      show-checkbox
      node-key="id"
      :default-expand-all="true"
      :default-checked-keys="selectedKeys"
      :props="defaultProps"
      @check="checked"
    />
  </div>
</template>
<script>
export default {
  props: {
    permissionList: {
      type: Array,
      default: () => []
    },
    value: { // 获取 组件 v-model绑定的值
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      data: this.permissionList,
      defaultProps: {
        children: 'children',
        label: 'label'
      },
      selectedKeys: []
    }
  },
  watch: {
    value: function (val) {
      this.selectedKeys = val
      // this.$refs.tree.setCheckedKeys(this.value)
    }
  },
  methods: {
    checked () {
      this.checkedArray = this.$refs.tree.getCheckedKeys().filter(item => item)
      this.$emit('input', this.checkedArray)
    }
  }
}
</script>

<style>
.el-tree-box .el-tree-node__children > div {
  float: left !important;
}
</style>
