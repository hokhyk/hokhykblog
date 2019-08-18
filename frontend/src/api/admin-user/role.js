import request from '@/utils/request'

export function getList(params) {
  return request({
    url: '/roles',
    method: 'get',
    params
  })
}

export function createRole(params) {
  return request({
    url: '/roles',
    method: 'post',
    data: params
  })
}

export function getRoleInfo(id) {
  return request({
    url: `/roles/${id}`,
    method: 'get'
  })
}

export function patchRoleInfo(id, params) {
  return request({
    url: `/roles/${id}`,
    method: 'patch',
    data: params
  })
}

export function deleteRoleInfo(id) {
  return request({
    url: `/roles/${id}`,
    method: 'delete'
  })
}

