import {NgModule} from '@angular/core';
import {HayCommonModule} from '../common/common.module';

import {Camera} from '@ionic-native/camera';
import {ImagePicker} from '@ionic-native/image-picker';

import {AppService} from '../common/services/app.service';
import {TimelineService} from './services/timeline.service';
import {TimelineTranslations} from './i18n/timeline-translations';

import {MyTimelinePage} from './pages/my-timeline';
import {TimelinePage} from './pages/timeline';
import {TimelineCreatePage} from './pages/timeline-create';
import {TimelineDetailPage} from './pages/timeline-detail';
import {TimelineCommentPage} from './pages/timeline-comment';
import {AlbumPreviewerPage} from "./pages/albumPreviewer";

@NgModule({
    imports: [
        HayCommonModule,
    ],
    declarations: [
        MyTimelinePage,
        TimelinePage,
        TimelineCreatePage,
        TimelineDetailPage,
        TimelineCommentPage,
        AlbumPreviewerPage
    ],
    entryComponents: [
        MyTimelinePage,
        TimelinePage,
        TimelineCreatePage,
        TimelineDetailPage,
        TimelineCommentPage,
        AlbumPreviewerPage
    ],
    providers: [
        TimelineService,
        Camera,
        ImagePicker,
    ],
    exports: [],
})
export class TimelineModule {
    constructor(public heyApp: AppService) {
        this.heyApp.loadTranslations(TimelineTranslations);

        this.subscribeEvents();
    }


    //
    // Subscribe events
    subscribeEvents() {
    }
}
