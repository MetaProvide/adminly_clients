import Vue from "vue";
import Router from "vue-router";
import { getRootUrl, generateUrl } from "@nextcloud/router";

import Home from "./Home";
import ClientModal from "./components/ClientModal";

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
			path: "/",
			component: Home,
			children: [
				{
					path: "/client-details/:clientId",
					component: ClientModal,
				},
			],
		},
	],
});

export default router;
