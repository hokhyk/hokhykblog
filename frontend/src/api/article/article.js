import request from '@/utils/request'

export function getList(params) {
  return request({
    url: '/articles',
    method: 'get',
    params
  })
}

export function getArticle(id) {
  return request({
    url: `/articles/${id}`,
    method: 'get'
  })
}

export function deleteArticle(id) {
  return request({
    url: `/articles/${id}`,
    method: 'delete'
  })
}

export function deleteMutiArticle(params) {
  return request({
    url: '/articles/muti',
    method: 'post',
    params
  })
}

export function createArticle(params) {
  return request({
    url: '/articles',
    method: 'post',
    data: params
  })
}

export function updateArticle(params) {
  return request({
    url: `/articles/${params.id}`,
    method: 'patch',
    data: params
  })
}

export function uploadImage(params) {
  return request({
    url: '/articles/image/upload',
    method: 'post',
    data: params
  })
}

export function uploadFile(params) {
  return request({
    url: '/articles/file/upload',
    method: 'post',
    data: params
  })
}
