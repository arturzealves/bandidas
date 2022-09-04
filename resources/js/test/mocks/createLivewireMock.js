const createLivewireMock = () => {
    return {
        emit: jest.fn().mockImplementation(function(eventName, object) {
            this.eventName = eventName;
            this.object = object;
        })
    };
};

export default createLivewireMock;
