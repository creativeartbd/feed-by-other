<template>
    <div>
        <b-media vertical-align="top" class="border-bottom pb-2">
            <template #aside>
                <b-img
                    :src="require('@/assets/images/google/google-merchant.png')"
                    blank-color="#ccc"
                    width="64"
                    alt="placeholder"
                />
            </template>
            <h5>Google Merchant</h5>
            <b-card-text>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. A quos pariatur porro non
                iusto fugit dolorum unde deserunt voluptatem dolore saepe dolores nostrum
                consectetur beatae, tenetur earum laboriosam illum! Quam.
            </b-card-text>
            <div class="demo-inline-spacing">
                <b-button variant="primary" v-b-modal.import-google-merchant> Import Google Merchant </b-button>
            </div>
        </b-media>

        <b-media vertical-align="top" class="border-bottom pt-2 pb-2">
            <template #aside>
                <b-img
                    :src="require('@/assets/images/google/adwords.png')"
                    blank-color="#ccc"
                    width="64"
                    alt="placeholder"
                />
            </template>
            <h5>Google Ads</h5>
            <b-card-text>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae consequuntur
                enim quod quidem. Porro ratione atque quam eos esse sint iste, quas, ipsam
                aspernatur unde enim molestias itaque deserunt blanditiis.
            </b-card-text>
            <div class="demo-inline-spacing">
                <b-button variant="primary" v-b-modal.import-google-ads> Import Goodle Ads </b-button>
            </div>
        </b-media>


        <b-media vertical-align="top" class="border-0 pt-2 pb-2">
            <template #aside>
                <b-img
                    :src="require('@/assets/images/google/google-analytics.png')"
                    blank-color="#ccc"
                    width="64"
                    alt="placeholder"
                />
            </template>
            <h5>Google Analytics</h5>
            <b-card-text>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae consequuntur
                enim quod quidem. Porro ratione atque quam eos esse sint iste, quas, ipsam
                aspernatur unde enim molestias itaque deserunt blanditiis.
            </b-card-text>
            <div class="demo-inline-spacing">
                <b-button variant="primary" v-b-modal.web-scrape-modal>Import google Analytics</b-button>
            </div>
        </b-media>

       
    </div>
</template>
