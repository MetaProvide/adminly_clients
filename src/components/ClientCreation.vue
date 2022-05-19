<template>
	<div>
		<button @click="toggleModal()">
			<svg
				width="24"
				height="18"
				viewBox="0 0 24 18"
				fill="none"
				xmlns="http://www.w3.org/2000/svg"
			>
				<path
					d="M15.9925 11.768C14.2255 10.8635 11.9326 10.1272 9.27154 10.1272C6.61052 10.1272 4.31763 10.8635 2.55062 11.768C2.03741 12.0323 1.60749 12.4337 1.30857 12.9276C1.00965 13.4214 0.853428 13.9885 0.857246 14.5658V17.4897H17.6858V14.5658C17.6858 13.3878 17.0442 12.3044 15.9925 11.768ZM9.27154 9.07543C11.596 9.07543 13.4787 7.19273 13.4787 4.86828C13.4787 2.54383 11.596 0.661133 9.27154 0.661133C6.94709 0.661133 5.06439 2.54383 5.06439 4.86828C5.06439 7.19273 6.94709 9.07543 9.27154 9.07543ZM20.8412 5.92007V2.76471H18.7376V5.92007H15.5823V8.02364H18.7376V11.179H20.8412V8.02364H23.9966V5.92007H20.8412Z"
					fill="#346188"
				/>
			</svg>
		</button>
		<Modal v-if="modal" @close="toggleModal()">
			<div class="modal-content">
				<form @submit.prevent="submitForm">
					<div>
						<label for="name">Name:</label><br />
						<input id="name" v-model="name" type="text" required />
					</div>
					<div>
						<label for="email">Email:</label><br />
						<input
							id="email"
							v-model="email"
							type="email"
							required
						/>
					</div>

					<div>
						<label for="description">Description:</label><br />
						<input
							id="description"
							v-model="description"
							type="text"
							required
						/>
					</div>
					<button :class="[name ? activeClass : '']" type="submit">
						Submit
					</button>
				</form>
			</div>
		</Modal>
	</div>
</template>

<script>
import { Modal } from "@nextcloud/vue";
import axios from "@nextcloud/axios";

export default {
	components: {
		Modal,
	},
	data() {
		return {
			modal: false,
			name: "",
			email: "",
			description: "",
			activeClass: "active",
		};
	},
	methods: {
		submitForm() {
			axios
				.post("create", {
					name: this.name,
					email: this.email,
					description: this.description,
				})
				.then((response) => {
					this.$emit("update-clients", true);
					this.toggleModal();
					this.name = "";
					this.email = "";
					this.description = "";
				})
				.catch((error) => {
					alert(error);
				});
		},
		toggleModal() {
			this.modal = !this.modal;
		},
	},
};
</script>

<style scoped>
.modal-container {
	width: 50vw;
}

input {
	width: 100%;
}

button {
	box-shadow: 0px 0px 10.8184px rgba(0, 0, 0, 0.15);
	border-radius: 8px;
	background-color: white;
}

.modal-content {
	padding: 20px;
}
</style>
