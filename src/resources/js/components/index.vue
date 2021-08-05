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
                            <li v-if="set_user == false"><a href="#" @click.prevent="showModalLogin">Đăng nhập</a></li>
                        </ul>
                    </nav>
                    <router-link to="/cart" v-if="set_user == true"><img src="../images/Cart.png" width="30px" height="30px"></router-link>
                    <div class="container_notification_menu" @click.prevent="showNotificationMenu">
                        <div class="numbers_new_noti pointer" v-if="numberNewNotis > 0">{{ numberNewNotis }}</div>
                        <img class="pointer" src="../images/notification.png" width="22px" height="22px">
                        <GetNotifications v-if="isLogin"/>
                        <NotificationMenu v-if="showNotification"/>
                    </div>
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
                <div class="row">
                    <div class="col-2">
                        <h1>Những sản phẩm công nghệ<br> chất lượng hàng đầu.!</h1>
                        <p>Web K3MN là nơi bạn có thể tìm thấy chúng - Hãy bắt đầu với chúng tôi</p>
                        <router-link to="/products" class="btn">Bắt đầu thôi &#8594;</router-link>
                    </div>
                    <div class="col-2">
                        <img src="../images/Banner.png">
                    </div>
                </div>
            </div>
        </div>
        
        <modal-login></modal-login>

    <!--Thể loại: có nghĩa là các cái ảnh(biểu diễn thể loại) bấm vào thì link đến các sản phẩm thuộc thể loại đó-->
        <div class="categories">
            <div class="small-container">
                <h2 class="title">Danh mục sản phẩm</h2>
                <div class="row">
                <div class="col-3">
                    <router-link to="/products/search/điện thoại">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Smartphone_icon_-_Noun_Project_283536.svg/1200px-Smartphone_icon_-_Noun_Project_283536.svg.png">
                        <label><strong>Điện thoại</strong></label>
                    </router-link>
                </div>
                <div class="col-3">
                    <router-link to="/products/search/laptop">
                        <img src="https://image.flaticon.com/icons/png/512/59/59505.png"> 
                        <label><strong>Laptop</strong></label>
                    </router-link>
                </div>
                <div class="col-3">
                    <router-link to="/products/category/11">
                        <img src="https://img.icons8.com/ios/452/apple-watch-apps.png">
                        <label><strong>Phụ kiện</strong></label>
                    </router-link>
                </div>
            </div>
            </div>
        </div>
        

        <div class="small-container">
            <h2 class="title">Sản phẩm được ưa chuộng</h2>
            <div class="row">
                <div v-for="(product, index) in topProduct" :key="index" class="col-4" @click="goToProduct(product.id)">
                    <img :src="'/uploads/imagesProduct/' + product.thumbnail">
                    <h4>{{ product.name }}</h4>
                    <p><b>{{ product.price | FomatPrice }}</b></p>
                </div>
            </div>
            <h2 class="title">Sản phẩm mới nhất</h2>
            <div class="row">
                <div class="col-4" v-for="(product, index) in hotProduct" :key="index" @click="goToProduct(product.id)">
                    <img :src="'/uploads/imagesProduct/' + product.thumbnail">
                    <h4>{{ product.name }}</h4>
                    <p><b>{{ product.price | FomatPrice }}</b></p>
                </div>
            </div>
        </div>

        <!-----footer---->
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
    import {mapActions, mapGetters} from 'vuex';
    import ModalLogin from './modals/ModalLogin.vue';
    import NotificationMenu from './NotificationMenu.vue';
    import GetNotifications from './notifications/GetNotificatoins.vue';

    export default {
        components: {
            ModalLogin,
            NotificationMenu,
            GetNotifications
        },

        data() {
            return {
                topProduct: [],
                categories: [],
                hotProduct: [],
                show_acc: false,
                showNotification: false,
            }
        },
        async created() {
            axios.get('/api/topproduct')
                .then(response => {
                    this.topProduct= response.data;
                    // console.log(this.topProduct);
                })
                .catch(function(){
					console.log('Loi tai top san pham ban chay');
				});
            axios.get('/api/hotproduct')
                .then(response => {
                    this.hotProduct= response.data;
                    // console.log(this.hotProduct);
                })
                .catch(function(){
					console.log('Loi tai san pham noi bat');
				});
        },

        computed: {
            ...mapGetters({
                user: 'info_user',
                numberNewNotis: 'numberNewNotis',
                isLogin: 'user_isset'
            }),
            set_user: function() {
                if( this.user.length == 0 ) {
                    return false;
                }
                return true;
            }
        },

        methods: {
            //logout nguoi dung
            logout(event) {
                event.preventDefault();
                axios.get('/api/logout')
                    .then(response => {
                        console.log(response);
                        this.$router.go();
                    })
                    .catch(function(){
						console.log('Loi dang xuat');
					});
			},

            goToProduct(id) {
                this.$router.push('/product/' + id);
            },

            showNotificationMenu: function() {
                this.showNotification = !this.showNotification;
                this.clickNotificationsMenu();
            },

            closeNotificationMenu: function() {
                if( this.showNotification ) {
                    this.showNotification = false;
                }
                return
            },

            ...mapActions({
                getUser: 'featchAPIGetUser',
                showModalLogin: 'showLogin',
                clickNotificationsMenu: 'clickMenuNotifications',
            })
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

<style src="../../css/css_home.css" scoped>