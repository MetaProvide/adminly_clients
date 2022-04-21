<template>
	<main>
		<CreateClient />
		<ClientsTable :clients="clients" />
	</main>
</template>

<script>
import CreateClient from "./components/CreateClient";
import ClientsTable from "./components/ClientsTable";

export default {
	name: "App",
	components: {
		CreateClient,
		ClientsTable,
	},
	props: {
		clients: {
			type: Array,
			default() {
				return [];
			},
		},
	},
};
</script>

<style scoped>
main {
	width: 100%;
	min-height: 100vh;
	padding: 20px;
	display: flex;
	justify-content: center;
	gap: 20px;
	flex-direction: column;
}
.clients {
	font-weight: bold;
}
</style>
