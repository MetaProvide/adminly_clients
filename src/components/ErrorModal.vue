<template>
	<div>
		<Modal @close="toggleModal()">
			<div class="modal-content">
				<p>
					{{ message }}
				</p>
				<button @click="toggleModal()">Close</button>
			</div>
		</Modal>
	</div>
</template>

<script>
import { Modal } from "@nextcloud/vue";

export default {
	components: {
		Modal,
	},
	props: {
		message: {
			type: String,
			default() {
				return "";
			},
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
button {
	box-shadow: 0px 0px 11px var(--adminly-grey);
	border-radius: 8px;
	background-color: var(--adminly-dark-blue);
	color: white;
	display: flex;
	margin-inline: auto;
	line-height: 1.25rem;
}

.modal-content {
	padding: 1.25rem;
}

p {
	margin: 1.25rem 1.25rem 2.25rem;
}
</style>
