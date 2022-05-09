<template>
	<main>
		<ClientsTable :clients="clientsList" />
	</main>
</template>

<script>
import ClientsTable from "./components/ClientsTable";
import { ClientsUtil } from "./utils";

export default {
	name: "App",
	components: {
		ClientsTable,
	},
	data() {
		return { clientsList: null };
	},
	async created() {
		this.clientsList = await ClientsUtil.fetchClients();
	},
};
</script>

<style scoped>
main {
	width: 80%;
	margin: auto;
	min-height: 100vh;
	padding: 20px;
	display: flex;
	gap: 20px;
	flex-direction: column;
	align-content: center;
}
</style>
