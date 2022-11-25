<template>
    <div>
        <b-row class="m-2">
            {{ finalArr }}
            <b-col md="12">
                <h3 v-if="Object.hasOwn(data, 'title')">{{ data.title }}</h3>
                <div class="d-inline">
                    <span v-if="Object.hasOwn(data, 'brand')" class="mr-2"
                        ><strong>Brand: </strong>{{ data.brand }}</span
                    >
                    <span v-if="Object.hasOwn(data, 'price')" class="mr-2"
                        ><strong>Price ({{ currency_code }}): </strong>
                        {{ data.price }}</span
                    >
                    <span v-if="Object.hasOwn(data, 'stock')" class="mr-2"
                        ><strong>Stock: </strong>{{ data.stock }}</span
                    >
                    <span
                        v-if="Object.hasOwn(data, 'availability')"
                        class="mr-2"
                        ><strong>Availability: </strong
                        >{{ data.availability }}</span
                    >
                </div>
                <hr />
            </b-col>
        </b-row>
        <b-row class="m-2">
            <b-col md="4">
                <carousel
                    :starting-image="2"
                    :images="images"
                    :auto-slide-interval="3000"
                ></carousel>
            </b-col>
            <b-col md="8">
                <table class=" table table-bordered">
                    <tr v-for="(item, key) in data" :key="key">
                        <td
                            v-if="key !== 'vgt_id' && key !== 'originalIndex'"
                            style="word-break: break-all"
                        >
                            <span>
                            </span>
                            <p>
                                <strong
                                    >{{ capitalizeFirstLetter(key.replace(/_/g, " ")), }}</strong
                                >
                            </p>
                            <span v-if="Array.isArray(item)">
                                <ol>
                                    <li v-for="(i, k) in item" :key="k">
                                        <a
                                            v-if="isValidUrl(i)"
                                            :href="i"
                                            target="_blank"
                                            >{{ i }}</a
                                        >
                                        <span
                                            v-else-if="typeof i == 'object' && k == 'date_add' && k == 'date_upd' "
                                            >Update by feed ({{
                                                i.id_feed
                                            }}) at
                                            {{
                                                new Date(
                                                    i.date_upd * 1000
                                                ).toLocaleDateString(
                                                    "en-US",
                                                    dateOptions
                                                )
                                            }}</span
                                        >
                                        <span v-else>{{ i }}</span>
                                    </li>
                                </ol>
                            </span>
                            <span v-else>
                                <span v-if="key == 'date_add'">
                                    {{ date_format(item, "Y-m-d H:i ") }}
                                </span>
                                <span v-else-if="key == 'date_upd'">
                                    {{ date_format(item, "Y-m-d H:i ") }}
                                </span>
                                <span
                                    v-else-if="
                                        key == 'aff_link' || key == 'link'
                                    "
                                >
                                    <a :href="item" target="_blank">{{
                                        item
                                    }}</a>
                                </span>
                                <span v-else-if="key === 'price'">
                                    {{ item }} ({{ currency_code }})
                                </span>
                                <span v-else>{{ item }}</span>
                            </span>
                        </td>
                    </tr>
                </table>
            </b-col>
        </b-row>
    </div>
</template>

<script>
import { BCarousel, BCarouselSlide } from "bootstrap-vue";
import Carousel from './Carousel.vue';
//import './carousel.js';

export default {
    components: {
        BCarousel,
        BCarouselSlide,
        Carousel
    },
    data() {
        return {
            images : [],
            mainProps: {
                center: true,
                // fluidGrow: true,
                blank: true,
                blankColor: "#f2f2f7",
                class: "import-img",
            },
            tableData: [],
            dateOptions: {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "numeric",
            },
            flattened : [],
            additional_image_link : [],
            newArray : [],
            finalArr : []
        };
    },
    props: ["data", "currency_code"],
    mounted() {
        // console.log( 'before mount');
        // console.log( this.currency_code );
        // console.log( this.data );
        this.loop( ['one', 'tqo'], this.flattened );
    },
    watch: {
        data(obj) {

            // Build the table
            for( let property in obj ) {
                if( Array.isArray( obj[ property ] ) ) {
                    this.newArray = []
                    this.loop( obj[property], this.newArray )
                    this.finalArr.push({
                        [property] : this.newArray
                    });
                } else if ( this.isValidUrl ( obj[ property ] ) ) {
                    this.finalArr.push({
                        [property] : '<a href="' + obj[ property ] + '">' + obj[ property ] + '</a>'
                    });
                } else {
                    this.finalArr.push({
                        [property] : obj[property]
                    });
                }
            }

            // console.log( newData )
            // this.images = [];
            // this.flattened = [];

            // if( newData.additional_image_link ) {
            //     this.loop( newData.additional_image_link, this.flattened )
            //     this.loop( newData.additional_image_link, this.additional_image_link )
            // }
            // if( newData.image_link ) {
            //     this.loop( newData.image_link, this.flattened )
            // }

            // if( Array.isArray(this.flattened) ) {
            //     this.flattened.forEach( ( item, index ) => {
            //         this.images.push({
            //             id : index,
            //             big : item,
            //             thumb : item
            //         });
            //     });
            // } else {
            //     this.images.push({
            //         id : 0,
            //         big : newData.image_link,
            //         thumb : newData.image_link,
            //     });
            // }
        },
    },
    methods: {
        loop(data, outputArray) {
            if(!Array.isArray(data)) { 
                outputArray.push(data);
            } else {
                data.forEach( ( element ) => {
                    if(Array.isArray(element)) {
                        this.loop(element, outputArray);
                    } else {
                        outputArray.push(element);
                    }
                })
            }
        },
        date_format(unix_timestamp, format) {
            const date = new Date(unix_timestamp * 1000);
            const dateObject = {
                Y: date.getFullYear(),
                m: String(date.getMonth()).padStart(2, "0"),
                d: String(date.getDate()).padStart(2, "0"),
                H: String(date.getHours()).padStart(2, "0"),
                i: String(date.getMinutes()).padStart(2, "0"),
                s: String(date.getSeconds()).padStart(2, "0"),
            };
            var dateString = "";
            for (let char of format) {
                if (char in dateObject) {
                    dateString += dateObject[char];
                } else {
                    dateString += char;
                }
            }
            return dateString;
        },
        Recursive(obj) {
            for (var k in obj) {
                if (k !== "additional_image_link") {
                    if (
                        typeof obj[k] == "object" &&
                        !Array.isArray(obj[k]) &&
                        obj[k] !== null
                    ) {
                        eachRecursive(obj[k]);
                    } else {
                        this.tableData.push({
                            [k]: obj[k],
                        });
                    }
                }
            }
        },
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        isValidUrl(urlString) {
            const pattern = new RegExp(
                '^([a-zA-Z]+:\\/\\/)?' + // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                '(\\#[-a-z\\d_]*)?$', // fragment locator
                'i'
            );
            return pattern.test(urlString);
        },
    },
};
</script>

<style scoped>
.carousel-indicators li {
    background-color: #669966;
}

.carousel-indicators .active {
    background-color: #ff7600;
}

ol li {
    list-style-position: inside;
}

.additional_image {
    display : flex;
}

.import-img {
    object-fit: cover;
    width: 90px;
    height: 90px;
    padding: 5px;
    border: 1px solid #e5e5e5;
    margin-bottom: 10px;
}
</style>
