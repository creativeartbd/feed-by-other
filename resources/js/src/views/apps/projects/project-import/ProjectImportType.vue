<template>
    <div>
        <b-media vertical-align="top" class="border-bottom pb-2">
            <template #aside>
                <b-img
                    :src="require('@/assets/images/projects/xml.png')"
                    blank-color="#ccc"
                    width="64"
                    alt="placeholder"
                />
            </template>
            <h5>XML Import</h5>
            <b-card-text>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. A quos pariatur porro non
                iusto fugit dolorum unde deserunt voluptatem dolore saepe dolores nostrum
                consectetur beatae, tenetur earum laboriosam illum! Quam.
            </b-card-text>
            <div class="demo-inline-spacing">
                <b-button variant="primary" v-b-modal.import-xml-modal> Import XML </b-button>
            </div>
        </b-media>

        <b-media vertical-align="top" class="border-bottom pt-2 pb-2">
            <template #aside>
                <b-img
                    :src="require('@/assets/images/projects/csv.png')"
                    blank-color="#ccc"
                    width="64"
                    alt="placeholder"
                />
            </template>
            <h5>CSV Import</h5>
            <b-card-text>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae consequuntur
                enim quod quidem. Porro ratione atque quam eos esse sint iste, quas, ipsam
                aspernatur unde enim molestias itaque deserunt blanditiis.
            </b-card-text>
            <div class="demo-inline-spacing">
                <b-button variant="primary" v-b-modal.import-csv-modal> Import CSV </b-button>
            </div>
        </b-media>


        <b-media vertical-align="top" class="border-0 pt-2 pb-2">
            <template #aside>
                <b-img
                    :src="require('@/assets/images/projects/scrape.png')"
                    blank-color="#ccc"
                    width="64"
                    alt="placeholder"
                />
            </template>
            <h5>Scraping the website</h5>
            <b-card-text>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae consequuntur
                enim quod quidem. Porro ratione atque quam eos esse sint iste, quas, ipsam
                aspernatur unde enim molestias itaque deserunt blanditiis.
            </b-card-text>
            <div class="demo-inline-spacing">
                <b-button variant="primary" v-b-modal.web-scrape-modal>Scraping the website</b-button>
            </div>
        </b-media>

       
    </div>
</template>
