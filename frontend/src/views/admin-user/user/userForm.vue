<template>
  <div class="app-container">
    <el-form
      ref="form"
      v-loading="dialogLoading"
      :model="form"
      label-width="120px"
      :rules="rules"
      style="max-width: 600px"
    >
      <el-form-item
        label="Name："
        prop="name"
      >
        <el-input
          v-model="form.name"
          placeholder="Input User Name"
        />
      </el-form-item>
      <el-form-item
        label="E-mail："
        prop="email"
      >
        <el-input
          v-model="form.email"
          placeholder="Input E-mail"
        />
      </el-form-item>
      <el-form-item label="Password：">
        <el-input
          v-model="form.password"
          placeholder="Input password"
        />
      </el-form-item>
      <el-form-item label="Roles：">
        <el-select v-model="form.role_id" filterable placeholder="Select a role" :disabled="isDisabled">
          <el-option
            v-for="item in roleList"
            :key="item.id"
            :label="item.display_name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Status：">
        <template>
          <el-radio v-model="form.confirmed" label="1">Apply</el-radio>
          <el-radio v-model="form.confirmed" label="0" :disabled="isDisabled">Disable</el-radio>
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
import { getList as getRoleList } from '@/api/admin-user/role'
import { createUser, getUserInfo, patchUser } from '@/api/admin-user/user'

export default {
  data () {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        role_id: null,
        confirmed: null // 角色状态： 启用 1， 禁用 0
      },
      roleList: [],
      dialogLoading: false,
      rules: {
        name: [{ required: true, message: 'Name is required.', trigger: 'blur' }],
        email: [
          { required: true, message: 'E-mail is required.', trigger: 'blur' },
          { type: 'email', message: 'E-mail format is wrong.', trigger: ['blur', 'change'] }
        ],
        password: [{ required: true, message: 'No empty passwords.', trigger: 'blur' }],
        role_id: [{ required: true, message: 'Please select a role.', trigger: 'change' }],
        confirmed: [{ required: true, message: 'Please setup the status.', trigger: 'change' }]
      },
      isDisabled: false,
      userId: null
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      getRoleList({}).then(response => {
        this.roleList = response.result.data
        // 获取 role id : 根据是否有id 判断为编辑还是创建
        if (this.$route.params.id) {
          this.userId = this.$route.params ? +this.$route.params.id : null
          this.getUserInfo()
        } else {
          // 新建用户： 密码和用户状态设置默认值
          this.$set(this.$data, 'form', {
            password: '654321',
            confirmed: '1'
          })
        }
      })
    },
    getUserInfo () {
      getUserInfo(this.userId).then((res) => {
        const data = res.result.data
        this.$set(this.$data, 'form', {
          name: data.name,
          email: data.email,
          password: '',
          role_id: data.roles.data[0].id,
          confirmed: data.confirmed === 0 ? '0' : '1'
        })
        this.isDisabled = data.roles.data[0].name === 'superadmin'
      })
    },
    submit () {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          this.dialogLoading = true
          if (!this.userId) {
            this.createdUser()
          } else {
            this.editUser()
          }
        }
      })
    },
    onCancel () {
      this.$router.push('/admin-users')
    },
    // createUser  or EditUser
    createdUser () {
      createUser(this.form).then(() => {
        this.dialogLoading = false
        this.$message({
          message: 'Successfully Added.',
          type: 'success'
        })
        this.$router.push('/admin-users')
      }).catch(() => {
        this.dialogLoading = false
      })
    },
    editUser () {
      patchUser(this.userId, this.form).then(() => {
        this.dialogLoading = false
        this.$message({
          message: 'Successfully modified.',
          type: 'success'
        })
        this.$router.push('/admin-users')
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
