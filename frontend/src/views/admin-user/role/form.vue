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
        label="ID："
        prop="name"
      >
        <el-input
          v-model="form.name"
          placeholder="Input ID："
        />
      </el-form-item>
      <el-form-item
        label="Name："
        prop="display_name"
      >
        <el-input
          v-model="form.display_name"
          placeholder="Input role name"
        />
      </el-form-item>
      <el-form-item label="Description：">
        <el-input
          v-model="form.description"
          type="textarea"
          placeholder="Input role description"
        />
      </el-form-item>
      <el-form-item label="Permissions：">
        <role-permission
          v-model="form.permission_id"
          :permission-list="permissions"
        />
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
import { createRole, getRoleInfo, patchRoleInfo } from '@/api/admin-user/role'
import { getList } from '@/api/admin-user/permission'
import RolePermission from './permission'

export default {
  components: {
    RolePermission
  },
  data () {
    return {
      form: {
        name: '',
        display_name: '',
        description: '',
        permission_id: []
      },
      permissions: [],
      isIndeterminate: false,
      dialogLoading: false,
      rules: {
        name: [{ required: true, pattern: /^[a-zA-Z]+$/, message: 'Name is required', trigger: 'blur' }],
        display_name: [{ required: true, message: 'Displayed name is required', trigger: 'blur' }]
      },
      roleId: null
    }
  },
  mounted () {
    this.getPermissions()
    // 获取 role id : 根据是否有id 判断为编辑还是创建
    if (this.$route.params.id) {
      this.roleId = this.$route.params ? +this.$route.params.id : null
      this.getRolesConfig()
    }
  },
  destroyed () {
    this.roleId = null
  },
  methods: {
    getPermissions () {
      getList().then((response) => {
        const array = response.result.data
        if (array && array.length > 0) {
          array.forEach((element, index) => {
            const permission = {
              label: element.group_name,
              children:
                element.value && element.value.length !== 0
                  ? this.dealSubChildrenPermission(element.value)
                  : []
            }
            this.permissions.push(permission)
          })
        }
      })
    },
    getRolesConfig () {
      getRoleInfo(this.roleId).then((res) => {
        const data = res.result.data
        this.$set(this.$data, 'form', {
          name: data.name,
          display_name: data.display_name,
          description: data.description,
          permission_id: data.permissions.data.length !== 0 ? data.permissions.data.map(item => item.id) : []
        })
      })
    },
    submit () {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          this.dialogLoading = true
          if (!this.roleId) {
            this.createdRole()
          } else {
            this.editRole()
          }
        }
      })
    },
    onCancel () {
      this.$router.push('/admin-user/role')
    },
    // createRole  or EditRole
    createdRole () {
      createRole(this.form).then(() => {
        this.dialogLoading = false
        this.$message({
          message: 'Sucessfully Added.',
          type: 'success'
        })
        this.$router.push('/admin-user/role')
      }).catch(() => {
        this.dialogLoading = false
      })
    },
    editRole () {
      patchRoleInfo(this.roleId, this.form).then(() => {
        this.dialogLoading = false
        this.$message({
          message: 'Successfully modified.',
          type: 'success'
        })
        this.$router.push('/admin-user/role')
      }).catch(() => {
        this.dialogLoading = false
      })
    },
    dealSubChildrenPermission: array => {
      const arr = array.map(item => {
        return { id: item.id, label: item.display_name }
      })
      return arr
    }
  }
}
</script>

<style scoped>
</style>
