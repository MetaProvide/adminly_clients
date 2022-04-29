<template>
	<div>
		<Modal @close="toggleModal()">
			<div class="modal-content">
				<div class="row">
					<Avatar :username="client.name" :size="100" />
					<div class="col ml-22">
						<h1>
							{{ client.name }}
						</h1>
						<p>{{ client.timezone }}</p>
						<p>{{ age }}</p>
					</div>
					<div class="col ml-22">
						<h3>Other Contacts</h3>
						<h3>Attachments</h3>
					</div>
				</div>
				<h3>About</h3>
				<p>
					{{ client.description }}
				</p>
			</div>
		</Modal>
	</div>
</template>

<script>
import { Modal } from "@nextcloud/vue";
import Avatar from "vue-avatar";

export default {
	components: {
		Modal,
		Avatar,
	},

	props: {
		client: {
			type: Object,
			default() {
				return {};
			},
		},
	},
	computed: {
		age() {
			return this.client.age + " Years Old";
		},
	},
	methods: {
		toggleModal() {
			this.$emit("toggle-modal", false);
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

.client-main {
	display: flex;
}

.modal-content h1 {
	font-weight: 500;
	font-size: 33px;
	line-height: 41px;
	color: #346188;
}

.modal-content h3 {
	font-weight: 600;
	font-size: 18px;
	line-height: 22px;
	color: #346188;
}

.col {
	display: flex;
	flex-direction: column;
}

.row {
	display: flex;
	flex-direction: row;
}

.ml-22 {
	margin-left: 22px;
}
</style>
