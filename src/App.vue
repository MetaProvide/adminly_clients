<template>
	<main>
		<ClientsTable :clients="clients" />
	</main>
</template>

<script>
import ClientsTable from "./components/ClientsTable";

export default {
	name: "App",
	components: {
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
