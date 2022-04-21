<template>
	<div>
		<button id="show-modal" @click="modalShow = true">Add Client</button>
		<Modal v-if="modalShow" @close="modalShow = false">
			<div class="modal__content">
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
			modalShow: false,
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
					this.modalShow = false;
					this.name = "";
					this.email = "";
					this.description = "";
				})
				.catch((error) => {
					alert(error);
				});
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

.modal__content {
	padding: 20px;
}
</style>
