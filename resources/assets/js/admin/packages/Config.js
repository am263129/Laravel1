import { getConfig } from '../../utils/storage';

export default function(Vue) {
    // This is a global mixin, it is applied to every vue instance
    Vue.mixin({
        data: function() {
            const cloudfrontPublicUrl = getConfig().cloudfront_public_url;
            return {
                get sm_poster() {
                    return `${cloudfrontPublicUrl}/posters/`;
                },
                get md_poster() {
                    return `${cloudfrontPublicUrl}/posters/`;
                },
                get lg_poster() {
                    return `${cloudfrontPublicUrl}/posters/`;
                },
                get sm_backdrop() {
                    return `${cloudfrontPublicUrl}/backdrops/`;
                },
                get md_backdrop() {
                    return `${cloudfrontPublicUrl}/backdrops/`;
                },
                get lg_backdrop() {
                    return `${cloudfrontPublicUrl}/backdrops/`;
                },
                get sm_cast() {
                    return `${cloudfrontPublicUrl}/casts/`;
                },
                get md_cast() {
                    return `${cloudfrontPublicUrl}/casts/`;
                },
                get lg_cast() {
                    return `${cloudfrontPublicUrl}/casts/`;
                }
            };
        }
    });
}
