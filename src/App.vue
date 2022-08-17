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
.col {
	display: flex;
	flex-direction: column;
}

.row {
	display: flex;
	flex-direction: row;
}

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

.icon::before {
	content: "";
	background-repeat: no-repeat;
	background-position: left;
	padding: 0.25rem 0.6rem;
	background-size: 1rem;
}

.edit-button {
	background-image: url("../img/edit.svg");
}

.close-button {
	background-image: url("../img/close.svg");
}

input::placeholder,
textarea::placeholder {
	color: rgba(4, 67, 160, 0.3);
}
</style>
