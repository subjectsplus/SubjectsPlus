/**
 * Created by cbrownroberts on 6/29/16.
 */

var GuideCollection = (function() {
    function GuideCollection(settings) {
        this.collectionId = settings.collectionId;
        this.title        = settings.title;
        this.description  = settings.description;
        this.shortform    = settings.shortform;
        this.subjects     = settings.subjects || [];
    }

    GuideCollection.prototype.getCollection = function () {
        return this.guideCollection;
    };

    GuideCollection.prototype.addSubjectToList = function (guideCollection) {
        this.subjects.push(guideCollection);
    };

    GuideCollection.prototype.removeSubjectFromList = function (index) {
        this.subjects.splice(index, 1);
    };
    return GuideCollection;
}());


var CollectionSubject = ( function () {
    function CollectionSubject(settings) {
        this.collectionSubjectId = settings.collectionSubjectId;
        this.collectionId        = settings.collectionId;
        this.subjectId           = settings.subjectId;
        this.sort                = settings.sort;
    }
    return CollectionSubject;
}());


var GuideCollectionList = (function () {
    function GuideCollectionList() {
        this.guideCollectionList = [];
    }

    GuideCollectionList.prototype.getList = function () {
        return this.guideCollectionList;
    };

    GuideCollectionList.prototype.addToList = function (guideCollection) {
        this.guideCollectionList.push(guideCollection);
    };

    GuideCollectionList.prototype.removeFromList = function (index) {
        this.guideCollectionList.splice(index, 1);
    };
    return GuideCollectionList;
}());