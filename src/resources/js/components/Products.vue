<template>
    <div>
          <!---lọc theo thể loại--->
        <div class="small-container">
          <div class="row row-2">
            <select @change="dieuhuongurl($event)">
              <option value="0">Tất cả sản phẩm</option>
              <option v-for="(category, index) in categories" :key="index" :value="category.id">{{ category.name }}</option>
            </select>
          </div>
          <transition name="fade">
            <router-view></router-view>
          </transition>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        data() {
            return {
                categories: [],
            }
        },

        mounted() {
          // axios.get('/api/profile')
          //     .then(response => {
          //         this.user = response.data;
          //         // console.log(this.user);
          //     })
          //     .catch(function(){
          //       console.log('Loi tai user');
          //     });
          axios.get('/api/category')
              .then(response => {
                  this.categories= response.data;
                  // console.log(this.categories);
              })
              .catch(function(){
                console.log('Loi tai danh muc san pham');
              });
        },

        methods: {
          dieuhuongurl(event) {
            event.preventDefault();
            var category_id = event.target.value;
            if (category_id == 0) {
              this.$router.push('/products');
            }
            else {
              this.$router.push('/products/category/' + category_id);
            }
          },
        },

        watch: {
          
        }
    }
</script>

<style src="../../css/css_products.css" scoped>