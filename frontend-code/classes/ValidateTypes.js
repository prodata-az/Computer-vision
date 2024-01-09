
export default class ValidateTypes {

  constructor(is_valid = 0, message = '', valid_text = '', status_text = '')
  {
    this.is_valid = is_valid;
    this.message = message;
    this.valid_text = valid_text;
    this.status_text = status_text;
  }

  update (is_valid = 0, message = '', valid_text = '', status_text = '') {
    this.is_valid = is_valid;
    this.message = message;
    this.valid_text = valid_text;
    this.status_text = status_text;
  }

  updateRequiredIsInvalid () {
    this.is_valid = 0;
    this.message = 'This field is required';
    this.valid_text = 'is-invalid';
    this.status_text = 'text-danger';
  }

  updateRequiredIsValid () {
    this.is_valid = 1;
    this.message = '';
    this.valid_text = 'is-valid';
    this.status_text = 'text-success';
  }

  nullify () {
    this.is_valid = 0;
    this.message = '';
    this.valid_text = '';
    this.status_text = '';
  }
}
