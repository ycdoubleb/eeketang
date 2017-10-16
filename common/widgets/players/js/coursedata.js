
var studentInfo = {
    id: 1234,
    name: "wskeee",
    school: ""
};

var tacheStateInfo = {
    tbkt: {
        state: 1,
        test: "pass", //pass or upPass
        subState: [1, 1, 1, 1]
    },
    lxzd: {
        state: 1,
        subState: [1, 1, 1, 1]
    },
    khcs: {
        state: 1,
        subState: [1]
    }
};


var stateLogInfo = {
    stateName: "tbkt",
    subStateIndex: -1,
    subStateTitle: ''
};


var smallFoodData = [
];
if (cosdate == null) {
    var cosdate = {
        studentInfo: studentInfo,
        tacheState: tacheStateInfo,
        stateLogInfo: stateLogInfo,
        studyTime: {
            taskTimeLong: 12,
            studiedTime: 0,
            LastTime: ""
        },
        testInfoRecord: {
            MaxScore: 90,
            theScore: 80,
            testCount: 2
        },
        ScoreRecord: {
            studyScore: 0,
            joinScore: 0,
            testScore: 0
        },
        smallFoodData: smallFoodData
    };
}
