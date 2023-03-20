<template>
	<main>
		<!-- <Overview /> -->
		<ClientsTable />
	</main>
</template>

<script>
import ClientsTable from "./components/ClientsTable.vue";
// import Overview from "./components/Overview";

export default {
	name: "App",
	components: {
		// Overview,
		ClientsTable,
	},
};
</script>
<style scoped>
main {
	min-width: 90%;
	padding: 20px;
	display: flex;
	align-self: flex-start;
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
}

.app-adminly_clients {
	background: linear-gradient(180deg, #ffffff 0%, #e4e5f8 100%);
	justify-content: center;
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

.open-button {
	background-image: url("../img/open.svg");
}

.close-button {
	background-image: url("../img/close.svg");
}

.edit-button {
	background-image: url("../img/edit.svg");
}

.email-button {
	background-image: url("../img/email-large.svg");
}

.delete-button {
	background-image: url("../img/trash.svg");
}

.input-invalid {
	border-color: red !important;
}

.input-invalid::placeholder {
	color: rgba(255, 0, 0, 0.7);
}

.error-tag {
	color: red;
}
</style>
