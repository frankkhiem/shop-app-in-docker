<template>
    <div>
        <div class="header">
          <div class="container">
            <div class="navbar">
                <div class="logo">
                    <a href="/home"><img src="../images/Logo.png" width="150px"></a>
                </div>
                <nav>
                    <ul>
                        <li><router-link to="/home">Trang chủ</router-link></li>
                        <li><router-link to="/products">Sản phẩm</router-link></li>
                        <li v-if="set_user == false"><a href="/login">Đăng nhập</a></li>
                    </ul>
                </nav>
                <router-link to="/cart" v-if="set_user == true"><img src="../images/Cart.png" width="30px" height="30px"></router-link>
                <div class="account">
                    <img v-if="set_user == true && user[0].avatar == null" class="avatar" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1CfdSF5Sdj53VRzQtJe8dgcoDLSyH5tK_sGgyhlfs91uiPe4FAg0u_nsBPDIGovorvso&usqp=CAU" width="30px" height="30px" @click="show_acc = !show_acc">
                        <img v-if="set_user == true && user[0].avatar != null" class="avatar" :src="'/uploads/avatar/' + user[0].avatar" width="30px" height="30px" @click="show_acc = !show_acc">
                    <div class="dropdown-menu" v-show="show_acc == true">
                         <p v-if="user[0] != null"><b>{{ user[0].name }}</b></p>
                        <hr>
                        <router-link to="/profile">Hồ sơ</router-link>
                        <router-link to="/listorders">Lịch sử mua hàng</router-link>
                        <a href="#" @click="logout($event)">Đăng xuất</a>
                    </div>
                </div>
          </div>
        </div>
        </div>
        <div class="small-container">
        <router-link :to="{name: 'ListOrders'}" class="btn">&#8678;  Quay lại lịch sử đặt hàng</router-link>
        <div v-if="user[0] != null">
            <h1>Thông tin đơn hàng của: {{ user[0].name }}</h1>
        </div>
        <hr>
        <div v-show="order.id != null" class="row">
            <div class="col20">
				<img :src="'/uploads/imagesProduct/' + order.thumbnail">
			</div>
            <div class="col70">
                <div class="small-row">
                <label class="infor" for="">Tên sản phẩm:</label>
                <p><b>{{ order.product_name }}</b></p>
            </div>
            <div class="small-row">
                <label class="infor" for="">Trạng thái đơn hàng:</label>
                <p><b>{{ order.status_name }}</b></p>
            </div>
            <div class="small-row">
                <label class="infor" for="">Địa chỉ giao hàng:</label>
                <p v-if="order.address_order != null"><b>{{ order.address_order }}</b></p>
                <p v-else><b>Không rõ</b></p>
            </div>
            <div class="small-row">
                <label class="infor" for="">Số điện thoại đặt hàng:</label>
                <p v-if="order.phone_order != null"><b>{{ order.phone_order }}</b></p>
                <p v-else><b>Không rõ</b></p>
            </div>
            <div class="small-row">
                <label class="infor" for="">Ngày đặt hàng:</label>
                <p><b>{{ order.created_at }}</b></p>
            </div>
            <div class="small-row" v-if="order.completed_date != null">
                <label class="infor" for="">Ngày hoàn thành đơn hàng:</label>
                <p><b>{{ order.completed_date }}</b></p>
            </div>
            <div class="small-row">
                <label class="infor" for="">Đơn giá:</label>
                <p v-if="order.sale_price != null"><b>{{ order.sale_price | FomatPrice}}</b></p>
            </div>
            <div class="small-row">
                <label class="infor" for="">Số lượng:</label>
                <p><b>{{ order.quantity }}</b></p>
            </div>            
            <div class="small-row">
                <label class="infor" for="">Tổng tiền:</label>
                <p v-if="order.sale_price != null"><b>{{ order.quantity * order.sale_price | FomatPrice}}</b></p>
            </div>
            </div>
            
        </div>
    </div>
    <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="footer-col-1">
                        <h3>Web K3MN</h3>
                    </div>
                    <div class="footer-col-2">
                        <h3>Sản phẩm của:</h3>
                        <ul>
                            <li>Nguyễn Quốc Khánh</li>
                            <li>Nguyễn Gia Khiêm</li>
                            <li>Nguyễn Huy Mạnh</li>
                            <li>Trần Minh Khương</li>
                            <li>Nguyễn Văn Nam</li>
                        </ul>
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
                order: {},
                show_acc: false,
            }
        },

        mounted() {
            axios.get('/api/order/' + this.id)
                .then(response => {
                    this.order = response.data[0];
                    // console.log(this.order);
                })
                .catch(function(){
                  console.log('Loi tai thong tin don dat hang');
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
<style src="../../css/css_OderDetail.css" scoped>



