using System;
using System.Collections.Generic;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Library.EntityTranslators;
using BusinessEntitiesAlias = Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using DataWebServiceAlias = Sante.EMR.SmartClient.Infrastructure.Data.DataWebService;
using Sante.EMR.SmartClient.Infrastructure.Interface.Services;
using System.Reflection;


namespace Sante.EMR.SmartClient.Infrastructure.Data.EntityTranslators
{
    public class PatientTranslator : EntityMapperTranslator<BusinessEntitiesAlias.Patient, DataWebServiceAlias.Patient>
    {

        protected override DataWebServiceAlias.Patient BusinessToService(IEntityTranslatorService service, BusinessEntitiesAlias.Patient value)
        {
            DataWebServiceAlias.Patient p = new DataWebServiceAlias.Patient();
            foreach (PropertyInfo source in value.GetType().GetProperties())
            {
                foreach (PropertyInfo target in p.GetType().GetProperties())
                {
                    if (target.Name.Equals(source.Name))
                    {
                        object o = source.GetValue(value, null);

                        target.GetSetMethod().Invoke(p, new object[] { o });
                    }
                }
            }

            return p;
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="service"></param>
        /// <param name="value"></param>
        /// <returns></returns>
        protected override BusinessEntitiesAlias.Patient ServiceToBusiness(IEntityTranslatorService service, DataWebServiceAlias.Patient value)
        { 
            BusinessEntitiesAlias.Patient p = new BusinessEntitiesAlias.Patient();
            foreach (PropertyInfo pi in p.GetType().GetProperties())
            {

               
               object o = pi.GetValue(value, null);
               pi.GetSetMethod().Invoke(p, new object[] { o });

            }

            return p;


            //try
            //{
            //    p.CaseNumber = value.CaseNumber;
            //    p.City = value.City;
            //    p.DOB = value.DOB;
            //    p.FirstName = value.FirstName;
            //    p.LastName = value.LastName;
            //    p.Phone = value.Phone;
            //    p.SSN = value.SSN;
            //    p.State = value.State;
            //    p.StreeAddress = value.StreeAddress;
            //    p.Zip = value.Zipcode;
            //}
            //finally
            //{
                
            //}

            return p;
        }
    }
}
