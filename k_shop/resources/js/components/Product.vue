<template>
    <div>
        <!-- Phan thong tin san pham -->
        
        <div class="small-container product-details-page" v-if="product[0] != null">
            <h1>Thông tin sản phẩm</h1>
            <div class="row">
                <div class="col2">
                    <img v-if="changeAvt == false" :src="'../uploads/imagesProduct/' + product[0].thumbnail" width="100%" id="ProductImg">
                    <img v-else :src="Avt_src" width="100%" height="500px" id="ProductImg">
				    <div class="small-img-row">
                         <div class="small-img-col" v-for="(image, index) in images" :key="index">
                            <img :src="'../uploads/imagesProduct/' + image" width="100%" class="small-img" @click="gotoAvatar(image)">
                        </div>
				    </div>
                
                </div>
                <div class="col2">
                    <h2>{{ product[0].name }}</h2>
                    <p><b>{{ product[0].price | FomatPrice }}</b></p>
                    <h3>Tình trạng : {{ product[0].status_product.name }}</h3>
                    <button v-if="product[0].status_product.name == 'Còn hàng'" class="btn" @click="buy($event, product[0].id)">Mua ngay</button>
                    <button v-if="product[0].status_product.name == 'Còn hàng'" class="btn" @click="add_cart($event, product[0].id)">Thêm vào giỏ hàng</button>
                    <button v-else class="btn" @click="buy($event, product[0].id)">Đặt trước</button>
			    </div>
            </div>
        </div>
        <!--Mo ta, danh gia, binh luan-->
	<div class="small-container">
		<h2>Thông số kỹ thuật</h2>
		<table>
			<tr>
				<th>Thuộc tính</th>
				<th>Mô tả</th>
			</tr>
			<tr v-for="(infor, index) in product[3]" :key="index">
				<td>{{ infor.attribute }}</td>
				<td>{{ infor.information }}</td>
			</tr>
		</table>
		<h2>Bình luận</h2>
		<div class="comment" v-for="(comment, index) in comments" :key="index">
			<h4 class="you" v-if="user[0] != null && comment.user_id == user[0].id"><strong>{{ comment.user_name }}</strong></h4>
            <h4 v-else><strong>{{ comment.user_name }}</strong></h4>
			<p>{{ comment.content }}</p>
        </div>
        <div>
            <button class="seeMore" v-if="cmt_current_page < cmt_last_page" @click="moreComments()">Xem thêm ></button>
        </div>
        <div class="input-comment">
            <textarea name="comments" cols="50" rows="5" v-model="new_comment.content" placeholder="nhập bình luận của bạn tại đây..."></textarea>
            <br>
            <button class="btn" @click="comment($event, new_comment)">Gửi</button>
        </div>
	</div>

	<!--title-->
	<div class="small-container" v-if="recommend_products[0] != null">
            <h1>Sản phẩm liên quan</h1>
			<div class="row">
				<div class="col-4" v-for="(recommend_product, index) in recommend_products" :key="index" @click="goToProduct(recommend_product.id)">
					<img :src="'/uploads/imagesProduct/' + recommend_product.thumbnail">
					<h4>{{ recommend_product.name }}</h4>
					<p><b>{{ recommend_product.price | FomatPrice }}</b></p>
				</div>
			</div>
	</div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        props: [
          'id',
        ],

        data() {
          return {
            product: [],
            comments: [],
            cmt_current_page: 1,
            cmt_last_page: 1,
            recommend_products: [],
            new_comment: {
                content: '',
            },
            images: [],
            show_acc: false,
            keyword: '',
            Avt_src: "",
            changeAvt: false,
          }
        },

        mounted() {
            axios.get('/api/product/' + this.id)
                .then(response => {
                    this.product = response.data;
                    if(response.data[0].image !== "") {
                        this.images = JSON.parse(response.data[0].image);
                    }
                    // console.log('Thong tin san pham');
                    // console.log(this.product);
                })
                .catch(function(err){
                  console.log(err);
                });
            axios.get(`/api/product/${this.id}/comments`)
                .then(response => {
                    this.comments = response.data.data;
                    this.cmt_last_page = response.data.last_page;
                    // console.log('Danh sach comments');
                    // console.log(this.comments);
                })
                .catch(function(err){
                  console.log(err);
                });
            axios.get('/api/product/' + this.id + '/recommend')
                .then(response => {
                    this.recommend_products = response.data;
                    // console.log('Mang danh sách san pham duoc goi y');
                    // console.log(this.recommend_products);
                })
                .catch(function(){
                    console.log('Loi tai san pham duoc goi y');
                });
        },

        computed: {
          ...mapGetters({
            user: 'info_user'
          }),
          set_user: function() {

            if( this.user.length == 0 ) {
              return false;
            }
            return true;
          }
        },

        methods: {
            buy(event, product_id) {
                event.preventDefault();
                if (!this.set_user) {
                    window.location.href = '/login';
                    return;
                } else {
                    this.$router.push('/order/product/' + product_id);
                    return;
                };
            },
            gotoAvatar(image){
                this.Avt_src = '../uploads/imagesProduct/' + image;
                this.changeAvt = true;
            },

            comment(event, comment) {
                event.preventDefault();
                if (comment.content == '') {
                    return;
                }
                if (this.user.length == 0) {
                    window.location.href = '/login';
                    return;
                }
                comment.user_id = this.user[0].id;
                comment.product_id = this.product[0].id;
                axios.post('/api/comment', comment)
                .then(response => {
                    // console.log(response.data);
                })
                .catch(function(){
                  console.log('Loi binh luan');
                });
                this.comments.unshift({user_id: this.user[0].id, user_name: this.user[0].name, content: comment.content});
                comment.content = '';
                // console.log(this.comments);
            },

            add_cart(event, product_id) {
                event.preventDefault();
                if (!this.set_user) {
                    window.location.href = '/login';
                    return;
                } else {
                    // this.$router.push('/order/product/' + product_id);
                    var cart = {
                        user_id: this.user[0].id,
                        product_id: product_id
                    };
                    axios.post('/api/cart', cart)
                    .then(response => {
                        this.$alert("Bạn đã thêm sản phẩm vào giỏ hàng!");
                    })
                    .catch(function(){
                        console.log("loi them vao gio hang");
                    });
                    return;
                };
            },

            search(event) {
                event.preventDefault();
                if (this.keyword == '') {
                    return;
                }
                this.$router.push('/products/search/' + this.keyword);
                this.keyword = '';
            },

            logout(event) {
                event.preventDefault();
                const user = {
                    id: this.user[0].id
                };
                axios.post('/api/logout', user)
                    .then(response => {
                        console.log(response);
						window.location.href = '/login';
                    })
                    .catch(function(){
						console.log('Loi dang xuat');
					});
			},

            moreComments() {
                this.cmt_current_page++;
                axios.get(`/api/product/${this.id}/comments?page=${this.cmt_current_page}`)
                .then(response => {
                    var more_comments = response.data.data;
                    more_comments.forEach(comment => {
                        this.comments.push(comment);
                    });
                    // console.log('Xem them danh sach comments');
                    // console.log(this.comments);
                })
                .catch(function(err){
                  console.log(err);
                });
            },
            goToProduct(id) {
                this.$router.push('/product/' + id);
                location.reload();
            }
        },

        watch: {
            
        },
        filters: {
            FomatPrice: function(value){
                return value.toLocaleString('it-IT', {style : 'currency', currency : 'VND'});
            },
        }
    }
</script>
<style src="../../css/css_product_details.css" scoped>