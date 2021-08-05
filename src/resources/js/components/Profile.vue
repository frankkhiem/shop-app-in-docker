<template>
<div>
	<div class="small-container userprofile-page">
		<div class="row">
			<div class="col2" v-if="user[0] != null">
				<h4>Ảnh đại diện</h4>
				<img class="avatar" v-if="user[0].avatar == null" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1CfdSF5Sdj53VRzQtJe8dgcoDLSyH5tK_sGgyhlfs91uiPe4FAg0u_nsBPDIGovorvso&usqp=CAU" @click="editAvt = true">
				<img class="avatar" v-if="user[0].avatar != null" :src="'/uploads/avatar/' + user[0].avatar" @click="editAvt = true">
				<br>
			</div>
			<div class="col2" v-if="user[0] != null">
				<h2>Thông tin người dùng</h2>
				<hr>
				<h4>Họ tên:</h4>
				<p>{{ user[0].name }}</p>
				<h4>Ngày sinh:</h4>
				<p>{{ user[0].birthday }}</p>
				<h4>Địa chỉ:</h4>
				<p>{{ user[0].address }}</p>
				<h4>Email:</h4>
				<p>{{ user[0].email }}</p>
				<h4>Vai trò:</h4>
				<p>{{ user[1].name }}</p>
				<button v-if="edit == true" @click="cancel()">X</button>
				<button v-if="edit == true" @click="save()">V</button>
				<button @click="edit = true" class="btn">Chỉnh sửa thông tin</button>
			</div>
		</div>	
	</div>
		<div class="modal-edit" v-if="editAvt == true || edit==true">
			<div class="modal-edit-Avt-content" v-if="editAvt==true">
				<div class="row">
					<img v-if="user[0].avatar == null" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1CfdSF5Sdj53VRzQtJe8dgcoDLSyH5tK_sGgyhlfs91uiPe4FAg0u_nsBPDIGovorvso&usqp=CAU">
				<img v-if="user[0].avatar != null" :src="'/uploads/avatar/' + user[0].avatar">
				<hr>
				<div class="choose-Avt">
					<h3>Thay đổi ảnh đại diện</h3>
					<input type="file" id="file" name="file" ref="file" @change="handleFileUpload()" v-if="editAvt == true">
				</div>
						<button class="btn-cancel" v-if="editAvt == true" @click="editAvt = false">Hủy</button>
					<button class="btn-save" v-if="editAvt == true" @click="saveAvt()">Lưu</button>
				</div>
				
			</div>
			<div class="modal-edit-info-content" v-if="edit==true">
					<div class="row">
						<div class="small-row">
								<h4>Họ tên:</h4>
				<input type="text" v-model="user[0].name" >
						</div>
						<div class="small-row">
								<h4>Ngày sinh:</h4>
				<input type="date" v-model="user[0].birthday" >
						</div>
						<div class="small-row">
									<h4>Địa chỉ:</h4>
				<input type="text" v-model="user[0].address" >
						</div>
						<div class="small-row">
									<h4>Email:</h4>
				<p>{{ user[0].email }}</p>
						</div>
						<div class="small-row">
								<h4>Vai trò:</h4>
				<p>{{ user[1].name }}</p>
						</div>
				<button v-if="edit == true" @click="cancel()" class="btn-cancel">Hủy</button>
				<button v-if="edit == true" @click="save()" class="btn-save">Lưu</button>
					</div>
			</div>
				</div>
	<!-----footer---->
</div>	
</template>

<script>
    export default {
				props: ['csrf',],
				
        data() {
            return {
                user: [],
								edit: false,
								editAvt: false,
								file: '',
								avatar: '',
            }
        },
				
        mounted() {
            axios.get('/api/profile').then( response => {
                this.user = response.data;
								this.cacheUser = Object.assign({}, this.user[0]);
            });				
        },

				methods: {
					save(){
						this.cacheUser = Object.assign({}, this.user[0]);
						var saveUser = this.user[0];
						axios.put('api/profile', saveUser, {
							headers: {
								'Authorization': `token ${this.csrf}`
							}
						}).then( response => {
							console.log(response);
						} );
						this.edit = false;
					},
					cancel() {
						this.user[0] = Object.assign({}, this.cacheUser);
						this.edit = false;
					},

					// xác nhận thay đổi ảnh đại diện
					saveAvt() {
						const config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                }
						//form data
						let formData = new FormData();
						formData.append('file', this.file);
						axios.post('/api/profile/avatar', formData, config)
						.then(response => {
												console.log('SUCCESS');
                        console.log(response.data);
												this.user[0].avatar = response.data;
												this.cacheUser.avatar = response.data;
						}) 
						.catch(function(){
											console.log('FAILURE!!');
						});
						this.editAvt = false;
					},
					handleFileUpload() {
						this.file = this.$refs.file.files[0];
					},

					//logout nguoi dung
					logout(event) {
						event.preventDefault();
						const user = {
							id: this.user[0].id
						};
						axios.post('/api/logout', user)
                .then(response => {
                    console.log(response);
										window.location.href = 'login';
                })
                .catch(function(){
									console.log('Loi dang xuat');
								});
					},
					//
				},

				watch: {
					edit(){
						// console.log(this.edit);
					},
				},
    }
</script>

<style src="../../css/cssProfile.css" scoped>