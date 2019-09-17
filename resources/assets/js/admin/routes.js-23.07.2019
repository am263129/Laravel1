import VueRouter from 'vue-router';

let routes = [
    {
        name: 'dashboard',
        path: '/',
        component: require('./views/dashboard')
    },
	{
		name: 'movies-manage',
		path: '/movies/manage',
		component: require('./views/movies/movies-manage')
	},

	{
		name: 'movie-api',
		path: '/movies/new-movie-api',
		component: require('./views/movies/new-movie-api.vue')
	},

	{
		name: 'movie-custom',
		path: '/movies/new-movie-custom',
		component: require('./views/movies/new-movie-custom.vue')
	},
	{
		path: '/movies/movie-edit/:id',
		name: 'movie_edit',
		component: require('./views/movies/movie-edit.vue')
	},
	{
		name: 'analysis-movie',
        path: '/movies/analysis/:id',
		component: require('./views/movies/analysis-movie.vue')
	},
	//Series
	{
		name: 'series-manage',
		path: '/series/manage',
		component: require('./views/series/manage')
	},
	{
		name: 'series-api',
		path: '/series/new-series-api',
		component: require('./views/series/new-series-api.vue')
	},

	{
		name: 'series-custom',
		path: '/series/new-series-custom',
		component: require('./views/series/new-series-custom.vue')
	},
	{
		name: 'series_manage_id',
		path: '/series/series-manage/:id',
		component: require('./views/series/series-manage.vue')
	},
	{
		name: 'new_series_episode',
		path: '/series/series-manage/:id/new-series-episode',
		component: require('./views/series/new-series-episode.vue')
	},

    {
        name: 'new_series_episode_custome',
        path: '/series/series-manage/:id/new-series-episode-custome',
        component: require('./views/series/new-series-episode-custome.vue')
    },
    {
        name: 'episode_edit',
        path: '/series/series-manage/:id/episode-edit',
        component: require('./views/series/episode-edit.vue')
    },

	{
		name: 'series_edit',
        path: '/series/series-edit/:id',
		component: require('./views/series/series-edit.vue')
	},
	{
		name: 'analysis-series',
        path: '/series/analysis-series/:id',
		component: require('./views/series/analysis-series.vue')
	},
    {
        path: '/top/manage',
        name: 'top-manage',
        component: require('./views/tops.vue')
    },


    {
        path: '/actors/manage',
        name: 'actors-manage',
        component: require('./views/actors/manage.vue')
    },

    {
        path: '/report/manage',
        name: 'report-manage',
        component: require('./views/reports/manage.vue')
    },
    {
        path: '/report/show/:id',
        name: 'report-show',
        component: require('./views/reports/report.vue')
    },

    {
        path: '/profile/manage',
        name: 'profile',
        component: require('./views/profile/profile.vue')
    },
    {
        path: '/profile/security',
        name: 'security',
        component: require('./views/profile/security.vue')
    },
    {
        path: '/users/manage',
        name: 'users-manage',
        component: require('./views/users/manage.vue')
    },
    {
        path: '/users/edit/:id',
        name: 'edit-user',
        component: require('./views/users/edit.vue')
    },
    {
        path: '/users/create',
        name: 'create-user',
        component: require('./views/users/create.vue')
    },
    {
        path: '/tv/manage',
        name: 'tv-manage',
        component: require('./views/tv/manage.vue')
    },

    {
        path: '/tv/create',
        name: 'tv-create',
        component: require('./views/tv/create.vue')
    },

    {
        path: '/tv/edit/:id',
        name: 'tv-edit',
        component: require('./views/tv/edit.vue')
    },

    {
        path: '/braintree/subscribe',
        name: 'braintree-subscribe',
        component: require('./views/braintree/subscribe.vue')
    },


    {
        path: '/settings/users/manage',
        name: 'admins-users-manage',
        component: require('./views/settings/users/manage.vue')
    },
    {
        path: '/settings/users/edit/:id',
        name: 'admins-user-edit',
        component: require('./views/settings/users/edit.vue')
    },

    {
        path: '/settings/users/create',
        name: 'admins-user-create',
        component: require('./views/settings/users/create.vue')
    },

    {
        path: '/settings/appearance/themes',
        name: 'themes',
        component: require('./views/settings/appearance/themes.vue')
    },

    {
        path: '/settings/appearance/footer',
        name: 'footer',
        component: require('./views/settings/appearance/footer.vue')
    },

    {
        path: '/settings/tmdb',
        name: 'tmdb-manage',
        component: require('./views/settings/tmdb/manage.vue')
    },
    {
        path: '/settings/transcoder/watermark',
        name: 'transcoder-watermark',
        component: require('./views/settings/transcoder-video/watermark.vue')
    },


    {
        path: '/support/manage',
        name: 'support-manage',
        component: require('./views/support/manage.vue')
    },
    {
        path: '/support/manage/request/:id',
        name: 'support-request',
        component: require('./views/support/request.vue')
    },
    {
        path: '/file/manager/folder/:link',
        name: 'file-manager',
        component: require('./views/filemanager/root.vue')
    },

    {
        path: '/categories/manage',
        name: 'categories-manage',
        component: require('./views/categories/manage.vue')
    },

    {
        path: '/category/create',
        name: 'create-category',
        component: require('./views/categories/create.vue')
    },
    {
        path: '/category/edit/:id',
        name: 'edit-category',
        component: require('./views/categories/edit.vue')
    },

    {
        path: '/help',
        name: 'help',
        component: require('./views/helper/info.vue')
    },
];

export default new VueRouter({
	routes
});

