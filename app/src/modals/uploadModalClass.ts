import { EventPublisher } from "@tsalliance/sdk";
import { ModalAPI } from "@tsalliance/wydget-vue";

export class ConfirmModal extends ModalAPI.Modal {
    private _onConfirmed?: ModalAPI.ModalAction;

    constructor(title: string, message: string, ondismiss?: ModalAPI.ModalAction) {
        super({ title, details: { message }, ondismiss, component: ConfirmModalComponent });
    }

    public onConfirmed(action: ModalAPI.ModalAction): ConfirmModal {
        this._onConfirmed = action;
        return this;
    }

    public async dismiss(): Promise<void> {        
        if(this._ondismiss) {
            if(!this._ondismiss(this)) return;
        }

        return EventPublisher.emitEvent(new ModalAPI.ModalDismissEvent(this));
    }

    public async confirm() {
        if(this._onConfirmed) {
            if(!await this._onConfirmed(this)) return;
        }

        this.dismiss();
    }

}