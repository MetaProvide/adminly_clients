import Vue from "vue";
import Router from "vue-router";
import { getRootUrl, generateUrl } from "@nextcloud/router";

import ClientModal from "./components/ClientModal.vue";
import ClientCreation from "./components/ClientCreation.vue";

Vue.use(Router);

const webRootWithIndexPHP = getRootUrl() + "/index.php";
const doesURLContainIndexPHP =
	window.location.pathname.startsWith(webRootWithIndexPHP);
const base = generateUrl(
	"apps/adminly_clients",
	{},
	{
		noRewrite: doesURLContainIndexPHP,
	}
);

const router = new Router({
	mode: "history",
	base,
	routes: [
		{
			path: "/client/:clientId",
			name: "ClientModal",
			component: ClientModal,
		},
		{
			path: "/new-client",
			name: "ClientCreation",
			component: ClientCreation,
		},
	],
});

export default router;
