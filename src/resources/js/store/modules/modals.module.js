const state = {
  showModalLogin: false,
}

const getters = {
  stateModalLogin: (state) => {
    return state.showModalLogin
  }
}

const mutations = {
  turnOnModalLogin: (state) => {
    state.showModalLogin = true
  },

  turnOffModalLogin: (state) => {
    state.showModalLogin = false
  },
}

const actions = {
  showLogin: ({commit}) => {
    commit('turnOnModalLogin')
  },

  closedLogin: ({commit}) => {
    commit('turnOffModalLogin')
  }

}

export default {
  state,
  getters,
  mutations,
  actions
}