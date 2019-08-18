import request from '@/utils/request'

export function getUserList(params) {
  return request({
    url: '/admin_users',
    method: 'get',
    params
  })
}

export function createUser(params) {
  return request({
    url: '/admin_users',
    method: 'post',
    data: params
  })
}

export function getUserInfo(id) {
  return request({
    url: `/admin_users/${id}`,
    method: 'get'
  })
}

export function patchUser(id, params) {
  return request({
    url: `/admin_users/${id}`,
    method: 'patch',
    data: params
  })
}

export function deleteUser(id) {
  return request({
    url: `/admin_users/${id}`,
    method: 'delete'
  })
}
