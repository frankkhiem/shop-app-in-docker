<template>
  <div>
    
    <!-- hiển thị danh sách sản phẩm trong giỏ hàng -->
    <div class="small-container">
      <div v-if="user[0] != null">
          <h1>Giỏ hàng của của: {{ user[0].name }}</h1>
          <h2 v-if="products.length > 0">Danh sách sản phẩm</h2>
          <h2 style="color: red" v-if="products.length == 0">Bạn không có sản phẩm nào trong giỏ hàng!</h2>
      </div>
      <div class="small-container cart-page" v-if="products.length > 0">
        <table>
          <tr>
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            <th>Cập nhật</th>
          </tr>
          <tr v-for="(product, index) in products" :key="index">
            <td>
              <div class="cart-info">
                <img :src="'/uploads/imagesProduct/' + product.thumbnail">
                <div>
                  <p>{{ product.name }}</p>
                  <small>Giá: {{ product.price | FomatPrice }}</small>
                  <br>
                </div>
              </div>
            </td>
            <td>
              <button v-show="product.quantity > 1" @click="sub(product)">-</button>
              <span>{{ product.quantity }}</span>
              <button @click="add(product)">+</button>
            </td>
            <td>{{ product.price*product.quantity | FomatPrice }}</td>
            <td>
              <button @click="delete_product_in_cart($event, product.id)">Xóa</button>
            </td>
          </tr>
        </table>
      </div>

      <div v-if="set_user == true && products.length > 0">
        <div>
          <label for="">Tên khách hàng</label>
          <input type="text" v-model="user[0].name" disabled>
        </div>
        <div>
          <label for="">Số điện thoại đặt hàng</label>
          <input type="text">
        </div>
        <div>
          <label for="">Địa chỉ đặt hàng</label>
          <input type="text">
        </div>
        <div>
          <label for="">Tổng tiền phải thanh toán:</label>
          <span>{{ total_cost }}</span>
        </div>
        <button @click="buy_cart()">Xác nhận mua hàng</button>
      </div>
    </div>
    <br><br><br>
  </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
      data() {
        return {
          products: [],
          total_cost: 0,
        }
      },

      async mounted() {
        axios.get('/api/cart')
                .then(response => {
                    this.products = response.data;
                    this.products.forEach(element => {
                      element.quantity = 1;
                    });
                    // console.log(this.products);
                })
                .catch(function(err){
                  console.log(err);
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
        delete_product_in_cart(event, id_in_cart) {
          event.preventDefault();
          this.$confirm("Are you sure?").then(() => {
            //do something...
            var index = this.products.findIndex(x => x.id === id_in_cart);
            axios.delete('/api/cart/' + id_in_cart)
                .then(response => {
                  console.log(response.data);
                  this.products.splice(index, 1);
                })
                .catch(function(){
                  console.log('Loi xoa san pham khoi gio hang');
                });
          });
        },

        sub(product) {
          if( product.quantity > 1 ) {
            product.quantity--;
          }
          console.log(product.quantity);
        },

        add(product) {
          product.quantity++;
          console.log(product.quantity);
        },

        async buy_cart() {
          await  this.products.forEach(element => {
            var order = {
              user_id: this.user[0].id,
              product_id: element.product_id,
              quantity: 1,
              sale_price: element.price,
              status_order_id: 1,
              phone_order: "0987654321",
              address_order: "144 Xuan Thuy",
            };
            axios.post('/api/order', order)
                .then(response => {
                    console.log(response.data);
                })
                .catch(function(){
                  console.log('Loi dat hang');
                });
          });
          axios.delete('/api/cart/delete/all')
                .then(response => {
                  console.log(response.data);
                  this.products = [];
                })
                .catch(function(){
                  console.log('Loi xoa san pham khoi gio hang');
                });
          this.$router.push('/listorders');
        }
      },

      filters: {
        FomatPrice: function(value){
            return value.toLocaleString('it-IT', {style : 'currency', currency : 'VND'});
        },
      },
    }
</script>

<style src="../../css/css_cart.css" scoped>