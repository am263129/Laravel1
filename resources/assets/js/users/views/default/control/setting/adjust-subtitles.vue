<style scoped>
    .vc-chrome {
        position: absolute;
        z-index: 1;
    }
</style>

<template>
    <div>
        <div class="settings">

            <div class="row">

                <div class="exit-icon" @click="$Helper.home()">
                    <exit-button></exit-button>
                </div>

            <!-- EXIT -->

            <div class="col-12 col-sm-8 offset-sm-2 p-sm-3 mt-5 h-100">

                <div class="title">
                    <p>{{$t('setting.adjust_subtitles')}}</p>
                </div>
                <hr>
                    <div class="btn-group">
                        <div class="ml-3">

                            <div class="dropdown">
                                <button class="btn btn-warning"  id="dropdownFontSize" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$t('setting.font_size')}}</button>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownFontSize">
                                    <li class="dropdown-item" @click="caption_style.fontSize = '15px'">25%</li>
                                    <li class="dropdown-item" @click="caption_style.fontSize = '20px'">50%</li>
                                    <li class="dropdown-item" @click="caption_style.fontSize = '25px'">75%</li>
                                    <li class="dropdown-item" @click="caption_style.fontSize = '30px'">100%</li>
                                </div>
                            </div>
                            </div>


                        <div class="ml-3">

                            <div class="dropdown">
                                <button class="btn btn-warning"  id="dropdownFontWeight" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{$t('setting.font_weight')}}</button>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownFontWeight">
                                        <li class="dropdown-item" @click="caption_style.fontWeight = 500">500</li>
                                        <li class="dropdown-item" @click="caption_style.fontWeight = 600">600</li>
                                        <li class="dropdown-item" @click="caption_style.fontWeight = 700">700</li>
                                        <li class="dropdown-item" @click="caption_style.fontWeight = 800">800</li>
                                        <li class="dropdown-item" @click="caption_style.fontWeight = 900">900</li>
                                        <li class="dropdown-item" @click="caption_style.fontWeight = 100">1000</li>
                                </div>
                            </div>
            </div>
                        <div class="ml-3">
                            <button class="btn btn-warning" @click="SHOW_FONT_PICKER">{{$t('setting.font_color')}}</button>
                            <chrome-picker v-if="show_font_picker" v-model="colors_font" />
                        </div>
                        <div class="ml-3">
                            <button class="btn btn-warning" @click="SHOW_BACKGROUND_PICKER">{{$t('setting.font_backgrond')}}</button>
                            <chrome-picker v-if="show_background_picker" v-model="colors_background" />
                        </div>
                    </div>
                <div class="col-12 mt-5">
                    <img src="/themes/default/img/background_caption.jpg" alt="caption_cover" width="100%">
                    <div class="col-12">
                        <div style="text-align: center;font-style: normal;font-variant-ligatures: normal;font-variant-caps: normal;font-weight: normal;font-stretch: normal;line-height: normal;font-family: sans-serif;white-space: pre-line;position: absolute;direction: ltr;writing-mode: horizontal-tb;left: 0px;bottom: 40px;right: 0px;height: 42px;background-color: rgba(0, 0, 0, 0);">
                            <p :style="caption_style">Do not go gentle into that good night. Rage, rage against the dying of the light</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-5">
                    <button class="btn btn-warning" @click="CHANGE">{{$t('setting.update')}}</button>
                </div>
            </div>
        </div>

      </div>
    </div>
</template>

<script>
    import {Chrome} from 'vue-color';
    import exitButton from '../utils/exit-button.vue';
    export default {
        data() {
            return {
                colors_font: {
                    hex: '#ffffff',
                    hsl: {
                        h: 150,
                        s: 0.5,
                        l: 0.2,
                        a: 1
                    },
                    hsv: {
                        h: 150,
                        s: 0.66,
                        v: 0.30,
                        a: 1
                    },
                    rgba: {
                        r: 25,
                        g: 77,
                        b: 51,
                        a: 1
                    },
                    a: 1
                },
                colors_background: {
                    hex: '#194d33',
                    hsl: {
                        h: 150,
                        s: 0.5,
                        l: 0.2,
                        a: 1
                    },
                    hsv: {
                        h: 150,
                        s: 0.66,
                        v: 0.30,
                        a: 1
                    },
                    rgba: {
                        r: 25,
                        g: 77,
                        b: 51,
                        a: 1
                    },
                    a: 1
                },
                show_font_picker: false,
                show_background_picker: false,
                caption_style: {
                    position: 'relative',
                    left: '0',
                    bottom: '0',
                    fontSize: '15px',
                    color: '#fff',
                    width: '90%',
                    display: 'inline',
                    backgroundColor: 'transparent',
                    fontWeight: '500',
                }
            }
        },

        components: {
            'exit-button': exitButton,
            'chrome-picker': Chrome,
        },

        watch: {
            colors_font() {
                this.caption_style.color = this.colors_font.hex;
            },
            colors_background() {
                this.caption_style.backgroundColor = 'rgba(' + this.colors_background.rgba.r + ',' + this.colors_background
                    .rgba.g + ',' + this.colors_background.rgba.b + ',' + this.colors_background.rgba.a + ')';
            },
        },

        methods: {
            CHANGE() {
                const caption = {
                    'font-size': this.caption_style.fontSize,
                    'background-color': this.caption_style.backgroundColor,
                    'font-weight': this.caption_style.fontWeight,
                    'color': this.caption_style.color
                };
                localStorage.setItem('caption', JSON.stringify(caption))
                this.$store.dispatch('SET_CAPTION', caption);
            },

            SHOW_FONT_PICKER() {
                this.show_font_picker = !this.show_font_picker;
            },
            SHOW_BACKGROUND_PICKER() {
                this.show_background_picker = !this.show_background_picker;
            }
        }
    };
</script>