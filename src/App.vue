<template>
	<main>
		<!-- <Overview /> -->
		<ClientsTable :clients="clientsList" @update-clients="updateClients" />
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
		return { clientsList: null };
	},
	async created() {
		this.clientsList = await ClientsUtil.fetchClients();
	},
	methods: {
		async updateClients() {
			this.clientsList = await ClientsUtil.fetchClients();
		},
	},
};
</script>

<style scoped>
main {
	width: 80%;
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
	background-repeat: no-repeat;
	background-position: center bottom;
	background-size: contain;
	background-image: url("./assets/clientsBackground.svg");
}
</style>
