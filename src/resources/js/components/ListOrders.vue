<template>
    <div>
        <div class="small-container">
        <div v-if="user[0] != null">
            <h1>Lịch sử mua hàng của: {{ user[0].name }}</h1>
            <h2>Danh sách đơn hàng</h2>
        </div>
        <div class="small-container cart-page">
		<table>
			<tr>
				<th>Sản phẩm</th>
				<th>Số lượng</th>
				<th>Tình trạng đơn hàng</th>
				<th>Cập nhật</th>
			</tr>
            <tr v-for="(order, index) in orders" :key="index">
                <td>
					<div class="cart-info">
						<img :src="'/uploads/imagesProduct/' + order.thumbnail">
						<div>
							<p>{{ order.product_name }}</p>
							<small>Giá: {{ order.sale_price | FomatPrice }}</small>
							<br>
							<a href="" @click="orderDetail($event, order.id)">Xem chi tiết</a>
						</div>
					</div>
				</td>
				<td><span>{{ order.quantity }}</span></td>
				<td v-if="order.status_name == 'Chờ xác nhận'">{{ order.status_name }}</td>
                <td v-else-if="order.status_name == 'Đang vận chuyển'" class="ship">{{ order.status_name }}</td>
                <td v-else class="complete">{{ order.status_name }}</td>
				<td>{{ order.updated_at }}</td>
            </tr>
        </table>
        </div>
    </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        data() {
            return {
                orders: [],
                totalAmount: [],
                totalOrders: -1,
            }
        },

        mounted() {
            axios.get('/api/listorders')
                .then(response => {
                    this.orders = response.data.listOrders;
                    this.totalAmount = response.data.totalAmount;
                    this.totalOrders = response.data.totalOrders;
                    // console.log(this.orders);
                    // console.log(this.totalAmount);
                    // console.log(this.totalOrders);
                })
                .catch(function(){
                  console.log('Loi tai danh sach don dat hang');
                });
        },

        computed: {
          ...mapGetters({
            user: 'info_user',
            set_user: 'user_isset',
          }),
        },

        methods: {
            orderDetail(event, id) {
                event.preventDefault();
                this.$router.push('/order/detail/' + id);
                return;
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
<style src="../../css/css_listOrders.css" scoped>
