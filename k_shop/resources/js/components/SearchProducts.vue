<template>
  <div>
    <h2>Kết quả tìm kiếm <strong>"{{ keyword }}"</strong>: {{ products.total }} sản phẩm</h2>
    <div class="row">
			<div class="col-4" v-for="(product, index) in products.data" :key="index" @click="goToProduct(product.id)">
				<img :src="'/uploads/imagesProduct/' + product.thumbnail">
				<h4>{{ product.name }}</h4>
				<p><b>{{ product.price | FomatPrice }}</b></p>
			</div>
	</div>
<div class="page-btn">
			<span v-show="current_page > 1" @click="current_page = current_page - 1">&#8592;</span>
			<span v-for="i in last_page" :key="i" @click="current_page = i">
				<a v-if="i == current_page" class="current_page">{{ i }}</a>
				<a v-if="i != current_page" class="page">{{ i }}</a>				
			</span>
			<span v-show="current_page < last_page" @click="current_page = current_page + 1">&#8594;</span>
		</div>
  </div>
</template>

<script>
    export default {
        props: [
            'keyword',
        ],

        data() {
            return {
                products: {},
                current_page: 1,
						    last_page: 1,
            }
        },

        mounted() {
          axios.get('/api/products?key=' + this.keyword)
                .then(response => {
                    this.products = response.data;
                    this.last_page = response.data.last_page;
                    // console.log(this.products);
                })
                .catch(function(){
                  console.log('Loi tai san pham theo keyword');
                });
        },

        watch: {
            keyword() {
                axios.get('/api/products?key=' + this.keyword)
                .then(response => {
                    this.products = response.data;
                    console.log(this.products);
                })
                .catch(function(){
                  console.log('Loi tai san pham theo keyword');
                });
            },
            current_page() {
              axios.get(`/api/products?key=${this.keyword}&page=${this.current_page}`)
              .then(response => {
                  this.products = response.data;
                  console.log(this.products);
                  console.log('thay doi phan trang');
              })
              .catch(function(){
                console.log('Loi tai tat ca san pham');
              });
					  }
            
        },
				methods: {
					goToProduct(id) {
            this.$router.push('/product/' + id);
          }
				},
				filters: {
            FomatPrice: function(value){
                return value.toLocaleString('it-IT', {style : 'currency', currency : 'VND'});
            },
        }
    }
</script>

<style src="../../css/css_products.css" scoped>