<template>
	<main>
		<!-- <Overview /> -->
		<ClientsTable
			:clients="clientsList"
			:is-empty="isEmpty"
			@update-clients="updateClients"
		/>
	</main>
</template>

<script>
import ClientsTable from "./components/ClientsTable";
import { ClientsUtil } from "./utils";
// import Overview from "./components/Overview";

export default {
	name: "App",
	components: {
		// Overview,
		ClientsTable,
	},
	data() {
		return { clientsList: null, isEmpty: false };
	},
	async created() {
		this.clientsList = await ClientsUtil.fetchClients();
	},
	methods: {
		async updateClients() {
			this.clientsList = await ClientsUtil.fetchClients();
			this.isEmpty = this.clientsList.length === 0;
		},
	},
};
</script>
<style scoped>
main {
	min-width: 90%;
	margin-inline: auto;
	margin-block-end: auto;
	padding: 20px;
	display: flex;
	gap: 20px;
	flex-direction: column;
	align-content: center;
}
</style>
<style>
.modal-footer button {
	font-family: "Roc Grotesk", var(--font-face);
}

.modal-footer {
	display: flex;
	padding-top: 1.5rem;
}

.app-adminly_clients {
	background: linear-gradient(180deg, #ffffff 0%, #e4e5f8 100%);
}

.svg {
	background-position: center;
	background-repeat: no-repeat;
	padding-inline: 1rem !important;
}

.edit-button {
	background-image: url("../img/edit.svg");
}

.close-button {
	background-image: url("../img/close.svg");
}
</style>
