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
	min-width: 80%;
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
.app-adminly_clients {
	background: linear-gradient(180deg, #ffffff 0%, #e4e5f8 100%);
}

.close-button {
	background-image: url("../img/close.svg");
	background-position: center;
	background-repeat: no-repeat;
}

.edit-button {
	background-image: url("../img/edit.svg");
	background-position: center;
	background-repeat: no-repeat;
	padding-inline: 1rem !important;
}

.delete-button {
	background-image: url("../img/trash.svg");
	background-position: center;
	background-repeat: no-repeat;
	padding-inline: 1rem !important;
}
</style>
