<template>
    <div>
        <b-row class="m-2">
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
                    :starting-image="0"
                    :images="images"
                    :auto-slide-interval="4500"
                ></carousel>
            </b-col>
            <b-col md="8">
                <table class="sidebar table table-bordered">
                    <tr v-for="( item, index ) in table" :key="index">
                        <td v-for="( value, key ) in item" :key="key">
                            <strong>
                                <p>{{ key }}</p>
                            </strong>
                            <ul v-if="Array.isArray(value) && key !== 'update_history'">
                                <li v-for="( item, index ) in value" :key="index">
                                    <a v-if="isValidUrl(item)" :href="item" target="_blank" rel="noreferrer noopener">{{ item }}</a>
                                    <span v-else>{{ item }}</span>
                                </li>
                            </ul>
                            <ul v-else-if="typeof value === 'object' && !Array.isArray(value)">
                                <li v-for="( item, index) in value" :key="index">
                                    {{ item }} - {{ value.item }} {{ index }}
                                </li>
                            </ul>
                            <p v-else-if="isValidUrl(value)">
                                <a :href="value" target="_blank" rel="noreferrer noopener">{{ value }}</a>
                            </p>
                            <p v-else-if="key === 'price'">
                                {{ value }} ({{ currency_code }})
                            </p>
                            <p v-else-if="key === 'date_upd'">
                                {{ date_format( value, "Y-m-d H:i ") }}
                            </p>
                            <p v-else-if="key === 'update_history'">
                                <ul>
                                    <li v-for="(obj, index) in value" :key="index">
                                            Update by {{ obj.feed_name }} ({{ obj.id_feed}}) on {{
                                                new Date(
                                                    obj.date_upd * 1000
                                                ).toLocaleDateString(
                                                    "en-US",
                                                    dateOptions
                                                )
                                            }}
                                    </li>
                               </ul>
                            </p>
                            <p v-else-if="key === 'date_add'">
                                {{ date_format( value, "Y-m-d H:i ") }}
                            </p>
                            <p v-else-if="key === 'availability'">
                                <span v-if="value === true">Yes</span>
                                <span v-else>No</span>
                            </p>
                            <p v-else>
                                {{ value }}
                            </p>
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
            table : []
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
            this.table = [];
            // Build the table
            for( let property in obj ) {
                if( property !== 'vgt_id' && property !== 'originalIndex' ) {
                    if( Array.isArray( obj[ property ] ) ) {
                        this.newArray = []
                        this.loop( obj[property], this.newArray )
                        this.table.push({
                            [property] : this.newArray
                        });
                    } else if ( this.isValidUrl ( obj[ property ] ) ) {
                        this.table.push({
                            [property] : obj[ property ]
                        });
                    } else {
                        this.table.push({
                            [property] : obj[property]
                        });
                    }
                }
            }

          
            this.images = [];
            this.flattened = [];

            if( obj.additional_image_link ) {
                this.loop( obj.additional_image_link, this.flattened )
                this.loop( obj.additional_image_link, this.additional_image_link )
            }

            if( obj.image_link ) {
                this.loop( obj.image_link, this.flattened )
            }

            if( Array.isArray(this.flattened) ) {
                this.flattened.forEach( ( item, index ) => {
                    this.images.push({
                        id : index,
                        big : item,
                        thumb : item
                    });
                });
            } else {
                this.images.push({
                    id : 0,
                    big : obj.image_link ? obj.image_link : '',
                    thumb : obj.image_link,
                });
            }
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
                '^(([a-zA-Z]+:)?\/\/)?' + // protocol
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

ul li {
    list-style-position: outside;
    margin-left : 10px;
}

.additional_image {
    display : flex;
}

.import-img {
    object-fit: contain;
    width: 90px;
    height: 90px;
    padding: 5px;
    border: 1px solid #e5e5e5;
    margin-bottom: 10px;
}
</style>
