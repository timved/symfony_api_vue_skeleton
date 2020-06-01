import axios from 'axios'

export default {
  subjects () {
    return axios.get('public/dictionary/subjects')
    .then(response => {
      return response.data
    })
  }
}