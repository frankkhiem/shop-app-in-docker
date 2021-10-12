const state = {
  user: [],
};

const getters = {
  info_user: (state) => {
    return state.user;
  },

  user_isset: (state) => {
    if( state.user.length == 0 ) {
      return false;
    }
    return true;
  },
};

const mutations = {
  updateUser: (state, info_user) => {
    state.user = info_user;
  }
};

const actions = {
  fetchAPIGetUser: async ({commit}) => {
    axios.get('/api/profile')
    .then( response => {
      commit('updateUser', response.data);
      return;
    })
    .catch( () => {
      return;
    });
  }
};

export default {
  state,
  getters,
  mutations,
  actions
}