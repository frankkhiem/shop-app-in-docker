<template>
    <div>
        <div class="small-container">
        <div v-if="product[0] != null && user[0] != null">
            <h1>Tiến hành đặt sản phẩm:</h1>
            
            <div>
                <div class="row">
                    <div class="col70">
                         <div class="small-row">
                    <label class="infor" for="">Tên khách hàng:</label>
                    <input class="infor-input" type="text" v-model="user[0].name" disabled>
                </div>
                <div class="small-row">
                    <label class="infor" for="">Số điện thoại đặt hàng:</label>
                    <input class="infor-input" type="text" v-model="order.phone_order">
                </div>
                <div class="small-row">
                    <label class="infor" for="">Địa chỉ đặt hàng:</label>
                    <input class="infor-input" type="text" v-model="order.address_order">
                </div>
                <div class="small-row">
                    <label class="infor1" for="">Đơn giá:</label>
                    <p>{{ product[0].price | FomatPrice}}</p>
                </div>
                <div class="small-row">
                    <label class="infor2" for="" margin-top="2.5px">Số lượng đặt hàng:</label>
                    <div>
                        <button @click="sub()" v-if="number > 1">-</button>
                        <button v-else @click="sub()" class="nosub">-</button>
                        <span>{{ number }}</span>
                        <button @click="add()">+</button>
                    </div>
                </div>
                    </div>
                    <div class="col20">
                        <h3>{{ product[0].name }}</h3>
                        <img :src="'/uploads/imagesProduct/' + product[0].thumbnail">
                    </div>
                </div>
               
                <hr>
            <div class="total-price">
			    <table>
				<tr>
					<td>Tổng tiền</td>
					<td>{{ this.number*this.product[0].price | FomatPrice}}</td>
				</tr>
				<tr>
					<td>Giảm giá</td>
					<td>{{ discout | FomatPrice}}</td>
				</tr>
				<tr>
					<td>Thanh toán</td>
					<td>{{ this.number*this.product[0].price - this.discout | FomatPrice}}</td>
				</tr>
			</table>
		</div>
                <button class="btn" @click="completeOrder()" v-show="completed == false">Hoàn tất</button>
                <button class="btn-cancel" @click="cancelOrder()" v-show="completed == false">Hủy</button>
                <div v-show="completed == true">
                    <h3>Bạn đã đặt hàng thành công</h3>
                    <router-link :to="{name: 'AllProducts'}" class="btn">Tiếp tục mua hàng</router-link>
                    <router-link :to="{name: 'ListOrders'}" class="btn">Lịch sử đặt hàng</router-link>
                </div>
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
                order: {},
                number: 1,
                completed: false,
                discout: 0,
                show_acc: false,
            }
        },

        mounted() {
            axios.get('/api/product/' + this.id)
                .then(response => {
                    this.product = response.data;
                    // console.log(this.product);
                })
                .catch(function(){
                  console.log('Loi tai thong tin san pham');
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
            sub() {
                if (this.number > 1) {
                    this.number--;
                }
            },
            add() {
                this.number++;
            },
            completeOrder() {
                this.order.user_id = this.user[0].id;
                this.order.product_id = this.product[0].id;
                this.order.quantity = this.number;
                this.order.sale_price = this.product[0].price;
                this.order.status_order_id = 1;
                this.order.thumbnail =  this.product[0].thumbnail
                axios.post('/api/order', this.order)
                .then(response => {
                    // console.log(response.data);
                    this.completed = true;
                })
                .catch(function(){
                  console.log('Loi dat hang');
                });
            },
            cancelOrder() {
                this.$router.push('/products');
            }
        },
        watch: {
            user() {
                if(this.user.length > 0) {
                    this.set_user = true;
                }
                else {
                    this.set_user = false;
                }
            }
        },
        filters: {
            FomatPrice: function(value){
                return value.toLocaleString('it-IT', {style : 'currency', currency : 'VND'});
            },
        }
    }
</script>
<style src="../../css/css_order.css" scoped>

